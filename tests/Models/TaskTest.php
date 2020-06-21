<?php
use TaskForce\Models\Task;
use TaskForce\Actions\ActionCancel;
use TaskForce\Actions\ActionComplete;
use TaskForce\Actions\ActionRespond;
use TaskForce\Actions\ActionRefuse;

require_once 'vendor/autoload.php';

$authorId = 1;
$assigneeId = 2;

$task = new Task($authorId, $assigneeId);
$actionCancel = new ActionCancel();
$actionComplete = new ActionComplete();
$actionRefuse = new ActionRefuse();
$actionRespond = new ActionRespond();

assert($task->currentStatus === Task::STATUS_NEW, 'new task status');

assert(
    $task->getNextStatus($actionCancel->getInternalName()) === Task::STATUS_CANCELED,
    'action cancel'
);
assert(
    $task->getNextStatus($actionRespond->getInternalName()) === $task->currentStatus,
    'action respond'
);
assert(
    $task->getNextStatus($actionComplete->getInternalName()) === Task::STATUS_COMPLETED,
    'action complete'
);
assert(
    $task->getNextStatus($actionRefuse->getInternalName()) === Task::STATUS_FAILED,
    'action refuse'
);
assert(
    $task->getNextStatus('unknown') === $task->currentStatus,
    'unknown action'
);

assert(
    $task->getAvailableActions($authorId, TASK::STATUS_NEW) == [$actionCancel],
    'action cancel for author'
);
assert(
    $task->getAvailableActions($assigneeId, TASK::STATUS_NEW) == [$actionRespond],
    'action respond for assignee'
);
assert(
    $task->getAvailableActions($authorId, TASK::STATUS_IN_PROGRESS) == [$actionComplete],
    'action complete for author'
);
assert(
    $task->getAvailableActions($assigneeId, TASK::STATUS_IN_PROGRESS) == [$actionRefuse],
    'action refuse for assignee'
);
assert(
    $task->getAvailableActions($authorId, Task::STATUS_FAILED) === [],
    'no actions for failed status'
);
assert(
    $task->getAvailableActions($authorId,Task::STATUS_COMPLETED) === [],
    'no actions for completed status'
);
assert(
    $task->getAvailableActions($assigneeId,Task::STATUS_CANCELED) === [],
    'no actions for canceled status'
);
assert(
    $task->getAvailableActions($assigneeId,'unknown') === [],
    'no actions for unknown status'
);

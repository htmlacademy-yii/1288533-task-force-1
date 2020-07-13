<?php
use TaskForce\Models\Task;
use TaskForce\Actions\ActionCancel;
use TaskForce\Actions\ActionComplete;
use TaskForce\Actions\ActionRespond;
use TaskForce\Actions\ActionRefuse;
use TaskForce\Exceptions\TaskStatusException;

require_once 'vendor/autoload.php';

$authorId = 1;
$assigneeId = 2;
$task = null;

try {
    $task = new Task(Task::STATUS_NEW, $authorId, $assigneeId);
} catch (TaskStatusException $error) {
    error_log($error->getMessage());
    die();
}

$actionCancel = new ActionCancel();
$actionComplete = new ActionComplete();
$actionRefuse = new ActionRefuse();
$actionRespond = new ActionRespond();

assert(
    $task->getNextStatus($actionCancel->getInternalName()) === Task::STATUS_CANCELED,
    'action cancel'
);
assert(
    $task->getNextStatus($actionRespond->getInternalName()) === Task::STATUS_NEW,
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
    $task->getNextStatus('unknown') === Task::STATUS_NEW,
    'unknown action'
);

assert(
    $task->getAvailableActions(TASK::ROLE_AUTHOR, TASK::STATUS_NEW) == [$actionCancel],
    'action cancel for author'
);
assert(
    $task->getAvailableActions(TASK::ROLE_ASSIGNEE, TASK::STATUS_NEW) == [$actionRespond],
    'action respond for assignee'
);
assert(
    $task->getAvailableActions(TASK::ROLE_AUTHOR, TASK::STATUS_IN_PROGRESS) == [$actionComplete],
    'action complete for author'
);
assert(
    $task->getAvailableActions(TASK::ROLE_ASSIGNEE, TASK::STATUS_IN_PROGRESS) == [$actionRefuse],
    'action refuse for assignee'
);
assert(
    $task->getAvailableActions(TASK::ROLE_AUTHOR, Task::STATUS_FAILED) === [],
    'no actions for failed status'
);
assert(
    $task->getAvailableActions(TASK::ROLE_AUTHOR,Task::STATUS_COMPLETED) === [],
    'no actions for completed status'
);
assert(
    $task->getAvailableActions(TASK::ROLE_ASSIGNEE,Task::STATUS_CANCELED) === [],
    'no actions for canceled status'
);
assert(
    $task->getAvailableActions(TASK::ROLE_ASSIGNEE,'unknown') === [],
    'no actions for unknown status'
);

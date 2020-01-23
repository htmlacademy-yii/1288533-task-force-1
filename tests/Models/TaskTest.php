<?php
use TaskForce\Models\Task;

require_once 'vendor/autoload.php';

$task = new Task(1);

assert($task->currentStatus === Task::STATUS_NEW, 'new task status');

assert(
    $task->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCELED,
    'cancel action'
);
assert(
    $task->getNextStatus(Task::ACTION_RESPOND) === $task->currentStatus,
    'respond action'
);
assert(
    $task->getNextStatus(Task::ACTION_COMPLETE) === Task::STATUS_COMPLETED,
    'complete action'
);
assert(
    $task->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED,
    'refuse action'
);
assert(
    $task->getNextStatus('unknown') === $task->currentStatus,
    'unknown action'
);

assert(
    $task->getAvailableActions($task->currentStatus) === [Task::ACTION_CANCEL, Task::ACTION_RESPOND],
    'current status'
);
assert(
    $task->getAvailableActions(Task::STATUS_NEW) === [Task::ACTION_CANCEL, Task::ACTION_RESPOND],
    'new status'
);
assert(
    $task->getAvailableActions(Task::STATUS_IN_PROGRESS) === [Task::ACTION_COMPLETE, Task::ACTION_REFUSE],
    'in progress status'
);
assert(
    $task->getAvailableActions(Task::STATUS_FAILED) === [],
    'failed status'
);
assert(
    $task->getAvailableActions(Task::STATUS_COMPLETED) === [],
    'completed status'
);
assert(
    $task->getAvailableActions(Task::STATUS_CANCELED) === [],
    'canceled status'
);
assert(
    $task->getAvailableActions('unknown') === [],
    'unknown status'
);

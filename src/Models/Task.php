<?php
declare(strict_types = 1);

namespace TaskForce\Models;

use TaskForce\Actions\AbstractAction;
use TaskForce\Actions\ActionCancel;
use TaskForce\Actions\ActionComplete;
use TaskForce\Actions\ActionRespond;
use TaskForce\Actions\ActionRefuse;
use TaskForce\Exceptions\TaskStatusException;
use TaskForce\Exceptions\AvailableActionException;

require_once 'vendor/autoload.php';

class Task
{
    public const STATUS_NEW = 'status_new';
    public const STATUS_CANCELED = 'status_canceled';
    public const STATUS_IN_PROGRESS = 'status_in_progress';
    public const STATUS_COMPLETED = 'status_completed';
    public const STATUS_FAILED = 'status_failed';

    public const ROLE_AUTHOR = 'role_author';
    public const ROLE_ASSIGNEE = 'role_assignee';

    /** @var string - Текущий статус таска */
    private $currentStatus;
    /** @var int - ID автора таска */
    private $authorId;
    /** @var int|null - ID исполнителя таска */
    private $assigneeId;

    /**
     * @param string $currentStatus - Текущий статус таска
     * @param int $authorId - ID автора таска
     * @param int|null $assigneeId - ID исполнителя таска
     *
     * @throws TaskStatusException - если передан несуществующий статус
     *
     * @return void
     */
    public function __construct(string $currentStatus, int $authorId, ?int $assigneeId = null)
    {
        $statuses = array_keys($this->getStatuses());

        if (!in_array($currentStatus, $statuses)) {
            throw new TaskStatusException('Несуществующий статус задания.');
        }

        $this->currentStatus = $currentStatus;
        $this->authorId = $authorId;
        $this->assigneeId = $assigneeId;
    }

    /**
     * Получить статусы
     *
     * @return array
     */
    public function getStatuses(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
        ];
    }

    /**
     * Получить действия
     *
     * @return array
     */
    public function getActions(): array
    {
        return [
            new ActionCancel(),
            new ActionRespond(),
            new ActionComplete(),
            new ActionRefuse(),
        ];
    }

    /**
     * Получить статус, в который перейдет таск в зависимости от действия
     *
     * @param string $action
     * @return string
     */
    public function getNextStatus(string $action): string
    {
        $status = $this->currentStatus;
        $actionCancel = new ActionCancel();
        $actionComplete = new ActionComplete();
        $actionRefuse = new ActionRefuse();

        switch ($action) {
            case $actionCancel->getInternalName():
                $status = self::STATUS_CANCELED;
                break;

            case $actionComplete->getInternalName():
                $status = self::STATUS_COMPLETED;
                break;

            case $actionRefuse->getInternalName():
                $status = self::STATUS_FAILED;
                break;
        }

        return $status;
    }

    /**
     * Получить доступные действия
     *
     * @param string $role - роль пользователя
     * @param string $status - статус таска
     *
     * @throws AvailableActionException - если передан несуществующий статус
     *
     * @return array
     */
    public function getAvailableActions(string $role, string $status): array
    {
        if (!in_array($role, [self::ROLE_AUTHOR, self::ROLE_ASSIGNEE])) {
            throw new AvailableActionException('Несуществующая роль.');
        }

        $actions = [];
        $authId = null;

        if ($role === self::ROLE_AUTHOR) {
            $authId = $this->authorId;
        }

        if ($role === self::ROLE_ASSIGNEE) {
            $authId = $this->assigneeId;
        }

        if ($status === self::STATUS_NEW) {
            $actions = $this->filterAvailableActionsByAuth(
                [new ActionCancel(), new ActionRespond()],
                $authId
            );
        }

        if ($status === self::STATUS_IN_PROGRESS) {
            $actions = $this->filterAvailableActionsByAuth(
                [new ActionComplete(), new ActionRefuse()],
                $authId
            );
        }

        return $actions;
    }

    /**
     * Отфильтровать доступные действия в зависимости
     * от авторизованного пользователя
     *
     * @param array AbstractAction[] $actions - действия
     * @param int $authId - ID авторизованного пользователя
     * @return array
     */
    private function filterAvailableActionsByAuth(array $actions, int $authId): array
    {
        $filtered =  array_filter(
            $actions,
            function(AbstractAction $action) use ($authId) {
                return $action->isAvailable(
                    $authId,
                    $this->authorId,
                    $this->assigneeId
                );
            }
        );

        return array_values($filtered);
    }
}

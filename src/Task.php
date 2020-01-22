<?php

class Task
{
    public const STATUS_NEW = 'status_new';
    public const STATUS_CANCELED = 'status_canceled';
    public const STATUS_IN_PROGRESS = 'status_in_progress';
    public const STATUS_COMPLETED = 'status_completed';
    public const STATUS_FAILED = 'status_failed';

    public const ACTION_CANCEL = 'action_cancel';
    public const ACTION_RESPOND = 'action_respond';
    public const ACTION_COMPLETE = 'action_complete';
    public const ACTION_REFUSE = 'action_refuse';

    /** @var string - Текущий статус таска */
    public $currentStatus = self::STATUS_NEW;

    /** @var int - ID автора таска */
    private $authorId;
    /** @var int|null - ID исполнителя таска */
    private $assigneeId;

    /**
     * @param int $authorId - ID автора таска
     * @param int|null $assigneeId - ID исполнителя таска
     * @return void
     */
    public function __construct(int $authorId, ?int $assigneeId = null)
    {
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
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_COMPLETE => 'Выполнено',
            self::ACTION_REFUSE => 'Отказаться',
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

        switch ($action) {
            case self::ACTION_CANCEL:
                $status = self::STATUS_CANCELED;
                break;

            case self::ACTION_COMPLETE:
                $status = self::STATUS_COMPLETED;
                break;

            case self::ACTION_REFUSE:
                $status = self::STATUS_FAILED;
                break;
        }

        return $status;
    }

    /**
     * Получить доступные действия для переданного статуса
     *
     * @param string $status
     * @return array
     */
    public function getAvailableActions(string $status): array
    {
        $actions = [];

        if ($status === self::STATUS_NEW) {
            $actions = [self::ACTION_CANCEL, self::ACTION_RESPOND];
        }

        if ($status === self::STATUS_IN_PROGRESS) {
            $actions = [self::ACTION_COMPLETE, self::ACTION_REFUSE];
        }

        return $actions;
    }
}

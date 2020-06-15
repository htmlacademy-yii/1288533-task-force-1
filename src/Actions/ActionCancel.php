<?php
namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;

class ActionCancel extends AbstractAction
{
    private const READABLE_NAME = 'Отменить';
    private const INTERNAL_NAME = 'action_cancel';

    public function getReadableName(): string
    {
        return self::READABLE_NAME;
    }

    public function getInternalName(): string
    {
        return self::INTERNAL_NAME;
    }

    public function isAvailable($authId, $authorId, $assigneeId): bool
    {
        return $authId === $authorId;
    }
}

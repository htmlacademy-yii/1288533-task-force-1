<?php
namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;

class ActionComplete extends AbstractAction
{
    private const READABLE_NAME = 'Выполнено';
    private const INTERNAL_NAME = 'action_complete';

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

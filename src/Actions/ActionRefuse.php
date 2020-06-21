<?php
namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;

class ActionRefuse extends AbstractAction
{
    private const READABLE_NAME = 'Отказаться';
    private const INTERNAL_NAME = 'action_refuse';

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
        return $authId === $assigneeId;
    }
}

<?php
declare(strict_types = 1);
namespace TaskForce\Actions;

use TaskForce\Actions\AbstractAction;

class ActionComplete extends AbstractAction
{
    private const READABLE_NAME = 'Выполнено';
    private const INTERNAL_NAME = 'action_complete';

    /** @inheritDoc */
    public function getReadableName(): string
    {
        return self::READABLE_NAME;
    }

    /** @inheritDoc */
    public function getInternalName(): string
    {
        return self::INTERNAL_NAME;
    }

    /** @inheritDoc */
    public function isAvailable($authId, $authorId, $assigneeId): bool
    {
        return $authId === $authorId;
    }
}

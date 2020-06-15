<?php
namespace TaskForce\Actions;

abstract class AbstractAction
{
    /**
     * Получить читабельное название действия
     *
     * @return string
     */
    abstract protected function getReadableName(): string;

    /**
     * Получить внутренее название действия
     *
     * @return string
     */
    abstract protected function getInternalName(): string;

    /**
     * Проверить, доступно ли право
     *
     * @param int $authId - ID авторизованного пользователя
     * @param int $authorId - ID автора таска
     * @param int|null $assigneeId - ID исполнителя таска
     * @return bool
     */
    abstract public function isAvailable(
        int $authId,
        int $authorId,
        ?int $assigneeId
    ): bool;
}

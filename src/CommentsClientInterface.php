<?php

declare(strict_types=1);

namespace Vendor\CommentsClient;

use Vendor\CommentsClient\Dto\Comment;
use Vendor\CommentsClient\Dto\ListOptions;
use Vendor\CommentsClient\Dto\NewCommentRequest;
use Vendor\CommentsClient\Dto\PaginatedComments;
use Vendor\CommentsClient\Dto\UpdateCommentRequest;
use Vendor\CommentsClient\Exception\ApiException;
use Vendor\CommentsClient\Exception\NetworkException;
use Vendor\CommentsClient\Exception\NotFoundException;
use Vendor\CommentsClient\Exception\SerializationException;
use Vendor\CommentsClient\Exception\ValidationException;

/**
 * Публичный контракт клиента сервиса комментариев.
 *
 * Объявляет три операции: получение страницы комментариев, создание комментария
 * и частичное обновление. Реализация не зависит от конкретной транспортной
 * библиотеки и собирается через внедрение зависимостей.
 */
interface CommentsClientInterface
{
    /**
     * Возвращает страницу комментариев согласно параметрам выборки.
     *
     * @throws NetworkException       при сбое сетевого взаимодействия
     * @throws ApiException           при ошибочном ответе сервиса
     * @throws SerializationException при невозможности разобрать ответ
     */
    public function list(ListOptions $options): PaginatedComments;

    /**
     * Создаёт новый комментарий.
     *
     * @throws NetworkException       при сбое сетевого взаимодействия
     * @throws ApiException           при ошибочном ответе сервиса
     * @throws ValidationException    при отклонении данных сервисом
     * @throws SerializationException при невозможности разобрать ответ
     */
    public function create(NewCommentRequest $request): Comment;

    /**
     * Частично обновляет комментарий по идентификатору.
     *
     * @throws NetworkException       при сбое сетевого взаимодействия
     * @throws ApiException           при ошибочном ответе сервиса
     * @throws NotFoundException      если комментарий с указанным идентификатором не найден
     * @throws ValidationException    при отклонении данных сервисом
     * @throws SerializationException при невозможности разобрать ответ
     */
    public function update(int $id, UpdateCommentRequest $request): Comment;
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Vendor\CommentsClient\Dto\Comment;
use Vendor\CommentsClient\Dto\ListOptions;
use Vendor\CommentsClient\Dto\NewCommentRequest;
use Vendor\CommentsClient\Dto\PaginatedComments;
use Vendor\CommentsClient\Dto\UpdateCommentRequest;

/**
 * Скелет HTTP-клиента сервиса комментариев.
 *
 * Зависимости транспортного слоя внедряются через конструктор и хранятся в
 * неизменяемых свойствах. Обнаружение реализаций PSR это ответственность
 * вызывающего кода, поэтому внутри клиента авто-обнаружение не используется.
 * Клиент не хранит состояние запроса между вызовами: все данные передаются
 * через аргументы методов.
 *
 * Тела методов это временные заглушки. HTTP-логика будет добавлена в следующих
 * фичах без изменения публичных сигнатур.
 */
final class CommentsClient implements CommentsClientInterface
{
    public function __construct(
        public readonly string $baseUrl,
        public readonly ClientInterface $httpClient,
        public readonly RequestFactoryInterface $requestFactory,
        public readonly StreamFactoryInterface $streamFactory,
        public readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function list(ListOptions $options): PaginatedComments
    {
        throw new \LogicException('Метод ещё не реализован');
    }

    public function create(NewCommentRequest $request): Comment
    {
        throw new \LogicException('Метод ещё не реализован');
    }

    public function update(int $id, UpdateCommentRequest $request): Comment
    {
        throw new \LogicException('Метод ещё не реализован');
    }
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Dto;

use Vendor\CommentsClient\Exception\ValidationException;

final class PaginatedComments
{
    public function __construct(
        public readonly CommentsList $items,
        public readonly Pagination $pagination,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        if (!array_key_exists('data', $payload)) {
            throw new ValidationException("Поле 'data': обязательный ключ отсутствует");
        }

        if (!array_key_exists('meta', $payload)) {
            throw new ValidationException("Поле 'meta': обязательный ключ отсутствует");
        }

        if (!is_array($payload['data'])) {
            throw new ValidationException("Поле 'data': ожидается массив");
        }

        if (!is_array($payload['meta'])) {
            throw new ValidationException("Поле 'meta': ожидается массив");
        }

        return new self(
            CommentsList::fromArray($payload['data']),
            Pagination::fromArray($payload['meta']),
        );
    }
}

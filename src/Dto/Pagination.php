<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Dto;

use Vendor\CommentsClient\Exception\ValidationException;

final class Pagination
{
    public readonly int $page;
    public readonly int $perPage;
    public readonly int $total;
    public readonly int $totalPages;

    public function __construct(
        int $page,
        int $perPage,
        int $total,
        int $totalPages,
    ) {
        if ($page < 1) {
            throw new ValidationException("Поле 'page': минимальное значение 1");
        }

        if ($perPage < 1) {
            throw new ValidationException("Поле 'perPage': минимальное значение 1");
        }

        if ($total < 0) {
            throw new ValidationException("Поле 'total': не может быть отрицательным");
        }

        if ($totalPages < 0) {
            throw new ValidationException("Поле 'totalPages': не может быть отрицательным");
        }

        $this->page = $page;
        $this->perPage = $perPage;
        $this->total = $total;
        $this->totalPages = $totalPages;
    }

    /**
     * @param array<string, mixed> $meta
     */
    public static function fromArray(array $meta): self
    {
        foreach (['page', 'perPage', 'total', 'totalPages'] as $metaKey) {
            if (!array_key_exists($metaKey, $meta)) {
                throw new ValidationException("Поле 'meta.{$metaKey}': обязательный ключ отсутствует");
            }
        }

        if (!is_int($meta['page'])) {
            throw new ValidationException("Поле 'meta.page': ожидается числовое значение");
        }

        if (!is_int($meta['perPage'])) {
            throw new ValidationException("Поле 'meta.perPage': ожидается числовое значение");
        }

        if (!is_int($meta['total'])) {
            throw new ValidationException("Поле 'meta.total': ожидается числовое значение");
        }

        if (!is_int($meta['totalPages'])) {
            throw new ValidationException("Поле 'meta.totalPages': ожидается числовое значение");
        }

        return new self(
            $meta['page'],
            $meta['perPage'],
            $meta['total'],
            $meta['totalPages'],
        );
    }

    public function hasNextPage(): bool
    {
        return $this->page < $this->totalPages;
    }

    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }

    public function isFirstPage(): bool
    {
        return 1 === $this->page;
    }

    public function isLastPage(): bool
    {
        return 0 === $this->totalPages || $this->page >= $this->totalPages;
    }
}

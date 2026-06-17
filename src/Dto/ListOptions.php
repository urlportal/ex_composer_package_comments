<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Dto;

/**
 * Параметры выборки списка комментариев.
 *
 * Диапазоны значений не валидируются: политика допустимых значений принадлежит
 * серверу. Имя класса выбрано шире пагинации для расширения в будущем (фильтры,
 * сортировка) без изменения публичного интерфейса. Объект неизменяем и
 * сериализуется в query-строку GET-запроса методом toArray.
 */
final class ListOptions
{
    public function __construct(public readonly int $page = 1, public readonly int $perPage = 20)
    {
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'perPage' => $this->perPage,
        ];
    }
}

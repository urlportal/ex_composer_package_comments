<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Dto;

use Vendor\CommentsClient\Exception\ValidationException;

/**
 * Данные для частичного обновления комментария.
 *
 * Хотя бы одно из полей должно быть задано. Заданные поля не могут быть пустыми.
 * Метод toArray возвращает только заданные поля, что поддерживает семантику
 * частичного обновления: сервер изменит лишь присланные значения.
 */
final class UpdateCommentRequest
{
    public readonly ?string $name;
    public readonly ?string $text;

    public function __construct(?string $name = null, ?string $text = null)
    {
        if (null === $name && null === $text) {
            throw new ValidationException('Должно быть задано хотя бы одно поле для обновления');
        }

        if (null !== $name && '' === trim($name)) {
            throw new ValidationException("Поле 'name' не может быть пустым");
        }

        if (null !== $text && '' === trim($text)) {
            throw new ValidationException("Поле 'text' не может быть пустым");
        }

        // Сохраняем исходные значения без обрезки, trim нужен только для проверки
        $this->name = $name;
        $this->text = $text;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $result = [];

        if (null !== $this->name) {
            $result['name'] = $this->name;
        }

        if (null !== $this->text) {
            $result['text'] = $this->text;
        }

        return $result;
    }
}

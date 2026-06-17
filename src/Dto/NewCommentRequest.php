<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Dto;

use Vendor\CommentsClient\Exception\ValidationException;

/**
 * Данные для создания нового комментария.
 *
 * Оба поля обязательны и не могут быть пустыми. Объект неизменяем после создания
 * и сериализуется в тело POST-запроса методом toArray.
 */
final class NewCommentRequest
{
    public readonly string $name;
    public readonly string $text;

    public function __construct(string $name, string $text)
    {
        if ('' === trim($name)) {
            throw new ValidationException("Поле 'name' не может быть пустым");
        }

        if ('' === trim($text)) {
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
        return [
            'name' => $this->name,
            'text' => $this->text,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Dto;

use Vendor\CommentsClient\Exception\ValidationException;

final class Comment
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $text;

    public function __construct(int $id, string $name, string $text)
    {
        if ($id < 0) {
            throw new ValidationException("Поле 'id' не может быть отрицательным");
        }

        if ('' === trim($name)) {
            throw new ValidationException("Поле 'name' не может быть пустым");
        }

        if ('' === trim($text)) {
            throw new ValidationException("Поле 'text' не может быть пустым");
        }

        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        foreach (['id', 'name', 'text'] as $key) {
            if (!array_key_exists($key, $data)) {
                throw new ValidationException("Поле '{$key}': обязательный ключ отсутствует");
            }
        }

        if (!is_int($data['id'])) {
            throw new ValidationException("Поле 'id': ожидается int, получен ".get_debug_type($data['id']));
        }

        if (!is_string($data['name'])) {
            throw new ValidationException("Поле 'name': ожидается string, получен ".get_debug_type($data['name']));
        }

        if (!is_string($data['text'])) {
            throw new ValidationException("Поле 'text': ожидается string, получен ".get_debug_type($data['text']));
        }

        return new self($data['id'], $data['name'], $data['text']);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
        ];
    }
}

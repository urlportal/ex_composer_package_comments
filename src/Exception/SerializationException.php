<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Exception;

/**
 * Ответ сервера не поддаётся разбору: некорректный JSON или неожиданная структура.
 */
final class SerializationException extends CommentsClientException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}

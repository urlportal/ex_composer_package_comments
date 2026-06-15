<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Exception;

/**
 * Данные не прошли проверку на стороне клиента до отправки запроса.
 *
 * Возникает при нарушении ограничений модели, например при пустом обязательном поле.
 */
final class ValidationException extends CommentsClientException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}

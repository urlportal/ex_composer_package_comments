<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Exception;

/**
 * Подкласс ApiException для ошибок HTTP 404.
 *
 * Код статуса зафиксирован в конструкторе: создать экземпляр с другим значением невозможно.
 */
final class NotFoundException extends ApiException
{
    public function __construct(
        string $message = '',
        string $responseBody = '',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 404, $responseBody, $previous);
    }
}

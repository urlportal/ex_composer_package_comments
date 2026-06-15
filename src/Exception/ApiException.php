<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Exception;

/**
 * Ошибка серверного ответа со статусом 400 и выше.
 *
 * Хранит код статуса HTTP и тело ответа, чтобы потребитель мог принять
 * решение по обработке. Не является final, потому что является
 * родителем для NotFoundException.
 */
class ApiException extends CommentsClientException
{
    private readonly int $statusCode;

    public function __construct(
        string $message,
        int $statusCode,
        private readonly string $responseBody,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);

        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): string
    {
        return $this->responseBody;
    }
}

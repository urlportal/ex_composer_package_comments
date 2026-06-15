<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Exception;

/**
 * Сетевая ошибка PSR-18 транспортного уровня.
 *
 * Исходное исключение HTTP-клиента передаётся через $previous.
 * Тип \Throwable выбран намеренно, чтобы не требовать psr/http-client.
 */
final class NetworkException extends CommentsClientException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Exception\ApiException;
use Vendor\CommentsClient\Exception\CommentsClientException;

/**
 * Тесты исключения для ответов сервера со статусом 400 и выше.
 */
final class ApiExceptionTest extends TestCase
{
    public function testStoresStatusCodeAndResponseBody(): void
    {
        $exception = new ApiException('сервер вернул ошибку', 503, '{"error":"unavailable"}');

        self::assertSame(503, $exception->getStatusCode());
        self::assertSame('{"error":"unavailable"}', $exception->getResponseBody());
    }

    public function testCodeIsAlignedWithStatusCode(): void
    {
        $exception = new ApiException('конфликт версий', 409, 'тело ответа');

        self::assertSame(409, $exception->getCode());
        self::assertSame(409, $exception->getStatusCode());
    }

    public function testMessageIsPropagated(): void
    {
        $exception = new ApiException('сообщение об ошибке', 400, '');

        self::assertSame('сообщение об ошибке', $exception->getMessage());
    }

    public function testPreviousIsPropagated(): void
    {
        $originalCause = new \RuntimeException('исходная ошибка');

        $exception = new ApiException('обёртка', 500, 'тело', $originalCause);

        self::assertSame($originalCause, $exception->getPrevious());
    }

    public function testCanBeCaughtAsCommentsClientException(): void
    {
        try {
            throw new ApiException('пойман по корню', 418, 'тело');
        } catch (CommentsClientException $caught) {
            self::assertInstanceOf(ApiException::class, $caught);
        }
    }

    public function testCanBeCaughtAsOwnType(): void
    {
        try {
            throw new ApiException('пойман по типу', 500, '');
        } catch (ApiException $caught) {
            self::assertSame(500, $caught->getStatusCode());
        }
    }
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Exception\CommentsClientException;
use Vendor\CommentsClient\Exception\NetworkException;

/**
 * Тесты исключения сетевого транспортного уровня.
 */
final class NetworkExceptionTest extends TestCase
{
    public function testCanBeCreatedWithDefaults(): void
    {
        $exception = new NetworkException();

        self::assertSame('', $exception->getMessage());
        self::assertNull($exception->getPrevious());
    }

    public function testCanBeCreatedWithMessage(): void
    {
        $exception = new NetworkException('таймаут соединения');

        self::assertSame('таймаут соединения', $exception->getMessage());
    }

    public function testWrapsPreviousThrowable(): void
    {
        $originalPsr18Failure = new \RuntimeException('исходная ошибка транспорта');

        $exception = new NetworkException('обёртка', $originalPsr18Failure);

        self::assertSame($originalPsr18Failure, $exception->getPrevious());
    }

    public function testCanBeCaughtAsCommentsClientException(): void
    {
        try {
            throw new NetworkException('пойман по корню');
        } catch (CommentsClientException $caught) {
            self::assertSame('пойман по корню', $caught->getMessage());
        }
    }

    public function testCanBeCaughtAsOwnType(): void
    {
        try {
            throw new NetworkException('пойман по типу');
        } catch (NetworkException $caught) {
            self::assertSame('пойман по типу', $caught->getMessage());
        }
    }

    public function testCanBeCaughtAsThrowable(): void
    {
        try {
            throw new NetworkException('пойман по интерфейсу');
        } catch (\Throwable $caught) {
            self::assertSame('пойман по интерфейсу', $caught->getMessage());
        }
    }
}

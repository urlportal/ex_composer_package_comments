<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Exception\CommentsClientException;
use Vendor\CommentsClient\Exception\SerializationException;

/**
 * Тесты исключения парсинга ответа сервера.
 */
final class SerializationExceptionTest extends TestCase
{
    public function testCanBeCreatedWithDefaults(): void
    {
        $exception = new SerializationException();

        self::assertSame('', $exception->getMessage());
        self::assertNull($exception->getPrevious());
    }

    public function testStoresMessage(): void
    {
        $exception = new SerializationException('некорректный JSON в теле ответа');

        self::assertSame('некорректный JSON в теле ответа', $exception->getMessage());
    }

    public function testPreviousIsPropagated(): void
    {
        $originalCause = new \RuntimeException('исходная ошибка парсера');

        $exception = new SerializationException('обёртка', $originalCause);

        self::assertSame($originalCause, $exception->getPrevious());
    }

    public function testCanBeCaughtAsCommentsClientException(): void
    {
        try {
            throw new SerializationException('пойман по корню');
        } catch (CommentsClientException $caught) {
            self::assertInstanceOf(SerializationException::class, $caught);
        }
    }

    public function testCanBeCaughtAsOwnType(): void
    {
        try {
            throw new SerializationException('пойман по типу');
        } catch (SerializationException $caught) {
            self::assertSame('пойман по типу', $caught->getMessage());
        }
    }
}

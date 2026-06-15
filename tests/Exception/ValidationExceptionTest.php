<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Exception\CommentsClientException;
use Vendor\CommentsClient\Exception\ValidationException;

/**
 * Тесты исключения валидации DTO до отправки запроса.
 */
final class ValidationExceptionTest extends TestCase
{
    public function testCanBeCreatedWithDefaults(): void
    {
        $exception = new ValidationException();

        self::assertSame('', $exception->getMessage());
        self::assertNull($exception->getPrevious());
    }

    public function testStoresMessage(): void
    {
        $exception = new ValidationException('пустое обязательное поле');

        self::assertSame('пустое обязательное поле', $exception->getMessage());
    }

    public function testPreviousIsPropagated(): void
    {
        $originalCause = new \RuntimeException('исходная ошибка');

        $exception = new ValidationException('обёртка', $originalCause);

        self::assertSame($originalCause, $exception->getPrevious());
    }

    public function testCanBeCaughtAsCommentsClientException(): void
    {
        try {
            throw new ValidationException('пойман по корню');
        } catch (CommentsClientException $caught) {
            self::assertInstanceOf(ValidationException::class, $caught);
        }
    }

    public function testCanBeCaughtAsOwnType(): void
    {
        try {
            throw new ValidationException('пойман по типу');
        } catch (ValidationException $caught) {
            self::assertSame('пойман по типу', $caught->getMessage());
        }
    }
}

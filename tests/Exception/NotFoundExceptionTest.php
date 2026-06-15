<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Exception\ApiException;
use Vendor\CommentsClient\Exception\CommentsClientException;
use Vendor\CommentsClient\Exception\NotFoundException;

/**
 * Тесты специализации ApiException для статуса HTTP 404.
 */
final class NotFoundExceptionTest extends TestCase
{
    public function testStatusCodeIsAlways404(): void
    {
        $exception = new NotFoundException();

        self::assertSame(404, $exception->getStatusCode());
        self::assertSame(404, $exception->getCode());
    }

    public function testStatusCodeIs404EvenWithCustomMessageAndBody(): void
    {
        $exception = new NotFoundException('ресурс не найден', '{"error":"missing"}');

        self::assertSame(404, $exception->getStatusCode());
        self::assertSame('ресурс не найден', $exception->getMessage());
        self::assertSame('{"error":"missing"}', $exception->getResponseBody());
    }

    public function testPreviousIsPropagated(): void
    {
        $originalCause = new \RuntimeException('исходная ошибка');

        $exception = new NotFoundException('обёртка', '', $originalCause);

        self::assertSame($originalCause, $exception->getPrevious());
    }

    public function testCanBeCaughtAsCommentsClientException(): void
    {
        try {
            throw new NotFoundException('пойман по корню');
        } catch (CommentsClientException $caught) {
            self::assertInstanceOf(NotFoundException::class, $caught);
        }
    }

    public function testCanBeCaughtAsApiException(): void
    {
        try {
            throw new NotFoundException('пойман по родителю');
        } catch (ApiException $caught) {
            self::assertSame(404, $caught->getStatusCode());
        }
    }

    public function testCanBeCaughtAsOwnType(): void
    {
        try {
            throw new NotFoundException('пойман по типу', 'тело');
        } catch (NotFoundException $caught) {
            self::assertSame('тело', $caught->getResponseBody());
        }
    }
}

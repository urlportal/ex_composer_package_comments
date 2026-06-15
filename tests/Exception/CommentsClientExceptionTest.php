<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Exception\CommentsClientException;

/**
 * Тесты корневого исключения иерархии.
 */
final class CommentsClientExceptionTest extends TestCase
{
    public function testClassIsAbstract(): void
    {
        $reflection = new \ReflectionClass(CommentsClientException::class);

        self::assertTrue(
            $reflection->isAbstract(),
            'CommentsClientException должен быть абстрактным классом',
        );
    }

    public function testExtendsRuntimeException(): void
    {
        $reflection = new \ReflectionClass(CommentsClientException::class);
        $parent = $reflection->getParentClass();

        self::assertNotFalse($parent, 'У CommentsClientException должен быть родительский класс');
        self::assertSame(\RuntimeException::class, $parent->getName());
    }

    public function testCanBeCaughtAsCommentsClientException(): void
    {
        $exception = $this->createDouble('сообщение из подкласса');

        try {
            throw $exception;
        } catch (CommentsClientException $caught) {
            self::assertSame('сообщение из подкласса', $caught->getMessage());
        }
    }

    public function testCanBeCaughtAsRuntimeException(): void
    {
        $exception = $this->createDouble('сообщение для родителя');

        try {
            throw $exception;
        } catch (\RuntimeException $caught) {
            self::assertSame('сообщение для родителя', $caught->getMessage());
        }
    }

    public function testCanBeCaughtAsThrowable(): void
    {
        $exception = $this->createDouble('сообщение для интерфейса');

        try {
            throw $exception;
        } catch (\Throwable $caught) {
            self::assertSame('сообщение для интерфейса', $caught->getMessage());
        }
    }

    /**
     * Возвращает анонимный наследник, чтобы протестировать создание объекта
     * через расширение, потому что напрямую абстрактный класс не создаётся.
     */
    private function createDouble(string $message): CommentsClientException
    {
        return new class($message) extends CommentsClientException {
        };
    }
}

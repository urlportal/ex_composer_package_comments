<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Dto;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Dto\UpdateCommentRequest;
use Vendor\CommentsClient\Exception\ValidationException;

final class UpdateCommentRequestTest extends TestCase
{
    // Успешное создание

    public function testConstructorWithOnlyName(): void
    {
        $request = new UpdateCommentRequest('Анна');

        $this->assertSame('Анна', $request->name);
        $this->assertNull($request->text);
    }

    public function testConstructorWithOnlyText(): void
    {
        $request = new UpdateCommentRequest(null, 'Новый текст');

        $this->assertNull($request->name);
        $this->assertSame('Новый текст', $request->text);
    }

    public function testConstructorWithBothFields(): void
    {
        $request = new UpdateCommentRequest('Анна', 'Новый текст');

        $this->assertSame('Анна', $request->name);
        $this->assertSame('Новый текст', $request->text);
    }

    public function testConstructorKeepsOriginalValuesWithoutTrim(): void
    {
        $request = new UpdateCommentRequest('  Анна  ');

        $this->assertSame('  Анна  ', $request->name);
    }

    // toArray исключает null-поля

    public function testToArrayContainsOnlyNameWhenTextIsNull(): void
    {
        $request = new UpdateCommentRequest('Анна');

        $this->assertSame(['name' => 'Анна'], $request->toArray());
        $this->assertArrayNotHasKey('text', $request->toArray());
    }

    public function testToArrayContainsOnlyTextWhenNameIsNull(): void
    {
        $request = new UpdateCommentRequest(null, 'Новый текст');

        $this->assertSame(['text' => 'Новый текст'], $request->toArray());
        $this->assertArrayNotHasKey('name', $request->toArray());
    }

    public function testToArrayContainsBothWhenBothSet(): void
    {
        $request = new UpdateCommentRequest('Анна', 'Новый текст');

        $this->assertSame(['name' => 'Анна', 'text' => 'Новый текст'], $request->toArray());
    }

    // Валидационные ошибки

    public function testConstructorThrowsWhenBothNull(): void
    {
        $this->expectException(ValidationException::class);

        new UpdateCommentRequest();
    }

    #[DataProvider('invalidDataProvider')]
    public function testConstructorThrowsOnEmptyGivenField(?string $name, ?string $text): void
    {
        $this->expectException(ValidationException::class);

        new UpdateCommentRequest($name, $text);
    }

    /**
     * @return iterable<string, array{?string, ?string}>
     */
    public static function invalidDataProvider(): iterable
    {
        yield 'пустое заданное имя' => ['', null];
        yield 'имя из пробелов' => ['   ', null];
        yield 'пустой заданный текст' => [null, ''];
        yield 'текст из пробельных символов' => [null, "\t\n"];
        yield 'оба заданы, но имя пустое' => ['', 'Текст'];
    }
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Dto;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Dto\NewCommentRequest;
use Vendor\CommentsClient\Exception\ValidationException;

final class NewCommentRequestTest extends TestCase
{
    // Успешное создание

    public function testConstructorCreatesObjectWithValidData(): void
    {
        $request = new NewCommentRequest('Анна', 'Привет');

        $this->assertSame('Анна', $request->name);
        $this->assertSame('Привет', $request->text);
    }

    public function testConstructorKeepsOriginalValuesWithoutTrim(): void
    {
        $request = new NewCommentRequest('  Анна  ', '  Привет  ');

        $this->assertSame('  Анна  ', $request->name);
        $this->assertSame('  Привет  ', $request->text);
    }

    // toArray

    public function testToArrayReturnsBothKeys(): void
    {
        $request = new NewCommentRequest('Анна', 'Привет');

        $this->assertSame(['name' => 'Анна', 'text' => 'Привет'], $request->toArray());
    }

    // Валидационные ошибки

    #[DataProvider('invalidDataProvider')]
    public function testConstructorThrowsOnInvalidData(string $name, string $text): void
    {
        $this->expectException(ValidationException::class);

        new NewCommentRequest($name, $text);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function invalidDataProvider(): iterable
    {
        yield 'пустое имя' => ['', 'Привет'];
        yield 'имя из пробелов' => ['   ', 'Привет'];
        yield 'имя из табуляции и перевода строки' => ["\t\n", 'Привет'];
        yield 'пустой текст' => ['Анна', ''];
        yield 'текст из пробелов' => ['Анна', '   '];
    }
}

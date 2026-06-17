<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Dto;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Dto\Comment;
use Vendor\CommentsClient\Exception\ValidationException;

final class CommentTest extends TestCase
{
    // Успешное создание через конструктор

    public function testConstructorCreatesObjectWithValidData(): void
    {
        $comment = new Comment(21, 'Анна', 'Привет');

        $this->assertSame(21, $comment->id);
        $this->assertSame('Анна', $comment->name);
        $this->assertSame('Привет', $comment->text);
    }

    public function testConstructorAllowsZeroId(): void
    {
        $comment = new Comment(0, 'Борис', 'Текст');

        $this->assertSame(0, $comment->id);
    }

    // fromArray

    public function testFromArrayCreatesObjectFromValidData(): void
    {
        $comment = Comment::fromArray(['id' => 21, 'name' => 'Анна', 'text' => 'Привет']);

        $this->assertSame(21, $comment->id);
        $this->assertSame('Анна', $comment->name);
        $this->assertSame('Привет', $comment->text);
    }

    // toArray

    public function testToArrayReturnsCorrectArray(): void
    {
        $comment = new Comment(21, 'Анна', 'Привет');

        $this->assertSame(['id' => 21, 'name' => 'Анна', 'text' => 'Привет'], $comment->toArray());
    }

    public function testToArrayRoundTrip(): void
    {
        $source = ['id' => 5, 'name' => 'Виктор', 'text' => 'Некий текст'];
        $comment = Comment::fromArray($source);

        $this->assertSame($source, $comment->toArray());
    }

    // Валидационные ошибки конструктора

    #[DataProvider('invalidConstructorDataProvider')]
    public function testConstructorThrowsOnInvalidData(int $id, string $name, string $text): void
    {
        $this->expectException(ValidationException::class);

        new Comment($id, $name, $text);
    }

    /**
     * @return iterable<string, array{int, string, string}>
     */
    public static function invalidConstructorDataProvider(): iterable
    {
        yield 'отрицательный id' => [-1, 'Анна', 'Привет'];
        yield 'пустое имя' => [1, '', 'Привет'];
        yield 'имя из пробелов' => [1, '   ', 'Привет'];
        yield 'пустой текст' => [1, 'Анна', ''];
        yield 'текст из пробельных символов' => [1, 'Анна', "\t\n"];
    }

    // Валидационные ошибки fromArray

    /**
     * @param array<string, mixed> $data
     */
    #[DataProvider('invalidFromArrayDataProvider')]
    public function testFromArrayThrowsOnInvalidData(array $data): void
    {
        $this->expectException(ValidationException::class);

        Comment::fromArray($data);
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function invalidFromArrayDataProvider(): iterable
    {
        yield 'нет ключа id' => [['name' => 'Анна', 'text' => 'Привет']];
        yield 'нет ключа name' => [['id' => 1, 'text' => 'Привет']];
        yield 'нет ключа text' => [['id' => 1, 'name' => 'Анна']];
        yield 'строка вместо int' => [['id' => '21', 'name' => 'Анна', 'text' => 'Привет']];
        yield 'int вместо строки' => [['id' => 1, 'name' => 42, 'text' => 'Привет']];
        yield 'отрицательный id' => [['id' => -5, 'name' => 'Анна', 'text' => 'Привет']];
    }
}

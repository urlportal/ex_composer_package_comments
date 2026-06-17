<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Dto;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Dto\Comment;
use Vendor\CommentsClient\Dto\CommentsList;
use Vendor\CommentsClient\Exception\ValidationException;

final class CommentsListTest extends TestCase
{
    private function makeComment(int $id = 1): Comment
    {
        return new Comment($id, 'Анна', 'Привет');
    }

    public function testConstructorCreatesObjectWithValidData(): void
    {
        $items = [$this->makeComment(1), $this->makeComment(2)];
        $list = new CommentsList($items);

        $this->assertSame($items, $list->items);
    }

    public function testConstructorAllowsEmptyItems(): void
    {
        $list = new CommentsList([]);

        $this->assertSame([], $list->items);
    }

    public function testFromArrayCreatesObjectFromRawComments(): void
    {
        $list = CommentsList::fromArray([
            ['id' => 21, 'name' => 'Анна', 'text' => 'Привет'],
            ['id' => 22, 'name' => 'Борис', 'text' => 'Здравствуйте'],
        ]);

        $this->assertCount(2, $list->items);
        $firstItem = $list->items[0];
        $this->assertInstanceOf(Comment::class, $firstItem);
        $this->assertSame(21, $firstItem->id);
        $this->assertSame('Анна', $firstItem->name);
    }

    public function testFromArrayWithEmptyArray(): void
    {
        $list = CommentsList::fromArray([]);

        $this->assertSame([], $list->items);
    }

    public function testGetIteratorReturnsArrayIterator(): void
    {
        $items = [$this->makeComment(1), $this->makeComment(2)];
        $list = new CommentsList($items);

        $this->assertInstanceOf(\ArrayIterator::class, $list->getIterator());
    }

    public function testForeachIteratesOverAllComments(): void
    {
        $items = [$this->makeComment(10), $this->makeComment(20)];
        $list = new CommentsList($items);

        $collected = [];
        foreach ($list as $comment) {
            $collected[] = $comment->id;
        }

        $this->assertSame([10, 20], $collected);
    }

    public function testForeachOnEmptyListProducesNoIterations(): void
    {
        $list = new CommentsList([]);

        $iterations = 0;
        foreach ($list as $ignored) {
            ++$iterations;
        }

        $this->assertSame(0, $iterations);
    }

    public function testCountReturnsNumberOfItems(): void
    {
        $list = new CommentsList([$this->makeComment(1), $this->makeComment(2)]);

        $this->assertSame(2, count($list));
    }

    public function testCountReturnsZeroForEmptyList(): void
    {
        $list = new CommentsList([]);

        $this->assertSame(0, count($list));
    }

    public function testConstructorThrowsWhenItemIsNotComment(): void
    {
        $this->expectException(ValidationException::class);

        // передаём намеренно неверный тип, чтобы проверить рантайм-валидацию конструктора
        // @phpstan-ignore argument.type
        new CommentsList(['не комментарий']);
    }

    public function testFromArrayThrowsWhenItemIsNotArray(): void
    {
        $this->expectException(ValidationException::class);

        CommentsList::fromArray(['не массив']);
    }

    public function testFromArrayPropagatesCommentValidationErrors(): void
    {
        $this->expectException(ValidationException::class);

        CommentsList::fromArray([
            ['id' => -1, 'name' => 'Анна', 'text' => 'Привет'],
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Dto;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Dto\Comment;
use Vendor\CommentsClient\Dto\CommentsList;
use Vendor\CommentsClient\Dto\PaginatedComments;
use Vendor\CommentsClient\Dto\Pagination;
use Vendor\CommentsClient\Exception\ValidationException;

final class PaginatedCommentsTest extends TestCase
{
    public function testConstructorCreatesObjectWithValidData(): void
    {
        $items = new CommentsList([new Comment(1, 'Анна', 'Привет')]);
        $pagination = new Pagination(1, 20, 1, 1);

        $paginated = new PaginatedComments($items, $pagination);

        $this->assertSame($items, $paginated->items);
        $this->assertSame($pagination, $paginated->pagination);
    }

    public function testFromArrayCreatesObjectFromServerPayload(): void
    {
        $payload = [
            'data' => [
                ['id' => 21, 'name' => 'Анна', 'text' => 'Привет'],
                ['id' => 22, 'name' => 'Борис', 'text' => 'Здравствуйте'],
            ],
            'meta' => ['page' => 2, 'perPage' => 20, 'total' => 137, 'totalPages' => 7],
        ];

        $paginated = PaginatedComments::fromArray($payload);

        $this->assertInstanceOf(CommentsList::class, $paginated->items);
        $this->assertInstanceOf(Pagination::class, $paginated->pagination);
        $this->assertCount(2, $paginated->items);
        $this->assertSame(21, $paginated->items->items[0]->id);
        $this->assertSame(2, $paginated->pagination->page);
        $this->assertSame(137, $paginated->pagination->total);
    }

    public function testFromArrayWithEmptyDataArray(): void
    {
        $paginated = PaginatedComments::fromArray([
            'data' => [],
            'meta' => ['page' => 1, 'perPage' => 20, 'total' => 0, 'totalPages' => 0],
        ]);

        $this->assertCount(0, $paginated->items);
        $this->assertTrue($paginated->pagination->isLastPage());
    }

    public function testFromArrayThrowsOnMissingDataKey(): void
    {
        $this->expectException(ValidationException::class);

        PaginatedComments::fromArray([
            'meta' => ['page' => 1, 'perPage' => 20, 'total' => 0, 'totalPages' => 0],
        ]);
    }

    public function testFromArrayThrowsOnMissingMetaKey(): void
    {
        $this->expectException(ValidationException::class);

        PaginatedComments::fromArray(['data' => []]);
    }

    public function testFromArrayThrowsWhenDataIsNotArray(): void
    {
        $this->expectException(ValidationException::class);

        PaginatedComments::fromArray([
            'data' => 'не массив',
            'meta' => ['page' => 1, 'perPage' => 20, 'total' => 0, 'totalPages' => 0],
        ]);
    }

    public function testFromArrayThrowsWhenMetaIsNotArray(): void
    {
        $this->expectException(ValidationException::class);

        PaginatedComments::fromArray([
            'data' => [],
            'meta' => 'не массив',
        ]);
    }

    public function testFromArrayDelegatesPaginationValidation(): void
    {
        $this->expectException(ValidationException::class);

        PaginatedComments::fromArray([
            'data' => [],
            'meta' => ['page' => 1, 'perPage' => 20, 'total' => 0],
        ]);
    }

    public function testFromArrayDelegatesCommentValidation(): void
    {
        $this->expectException(ValidationException::class);

        PaginatedComments::fromArray([
            'data' => [['id' => -1, 'name' => 'Анна', 'text' => 'Привет']],
            'meta' => ['page' => 1, 'perPage' => 20, 'total' => 1, 'totalPages' => 1],
        ]);
    }
}

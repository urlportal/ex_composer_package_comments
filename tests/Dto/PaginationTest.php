<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Dto;

use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Dto\Pagination;
use Vendor\CommentsClient\Exception\ValidationException;

final class PaginationTest extends TestCase
{
    public function testConstructorCreatesObjectWithValidData(): void
    {
        $pagination = new Pagination(2, 20, 137, 7);

        $this->assertSame(2, $pagination->page);
        $this->assertSame(20, $pagination->perPage);
        $this->assertSame(137, $pagination->total);
        $this->assertSame(7, $pagination->totalPages);
    }

    public function testConstructorAllowsEmptyState(): void
    {
        $pagination = new Pagination(1, 20, 0, 0);

        $this->assertSame(0, $pagination->total);
        $this->assertSame(0, $pagination->totalPages);
    }

    public function testFromArrayCreatesObjectFromMeta(): void
    {
        $pagination = Pagination::fromArray([
            'page' => 2,
            'perPage' => 20,
            'total' => 137,
            'totalPages' => 7,
        ]);

        $this->assertSame(2, $pagination->page);
        $this->assertSame(137, $pagination->total);
    }

    public function testNavigationOnFirstPageOfMany(): void
    {
        $pagination = new Pagination(1, 20, 137, 7);

        $this->assertTrue($pagination->isFirstPage());
        $this->assertFalse($pagination->isLastPage());
        $this->assertFalse($pagination->hasPreviousPage());
        $this->assertTrue($pagination->hasNextPage());
    }

    public function testNavigationOnMiddlePage(): void
    {
        $pagination = new Pagination(4, 20, 137, 7);

        $this->assertFalse($pagination->isFirstPage());
        $this->assertFalse($pagination->isLastPage());
        $this->assertTrue($pagination->hasPreviousPage());
        $this->assertTrue($pagination->hasNextPage());
    }

    public function testNavigationOnLastPage(): void
    {
        $pagination = new Pagination(7, 20, 137, 7);

        $this->assertFalse($pagination->isFirstPage());
        $this->assertTrue($pagination->isLastPage());
        $this->assertTrue($pagination->hasPreviousPage());
        $this->assertFalse($pagination->hasNextPage());
    }

    public function testNavigationOnSinglePage(): void
    {
        $pagination = new Pagination(1, 20, 5, 1);

        $this->assertTrue($pagination->isFirstPage());
        $this->assertTrue($pagination->isLastPage());
        $this->assertFalse($pagination->hasPreviousPage());
        $this->assertFalse($pagination->hasNextPage());
    }

    public function testNavigationWhenTotalPagesIsZero(): void
    {
        $pagination = new Pagination(1, 20, 0, 0);

        $this->assertTrue($pagination->isFirstPage());
        $this->assertTrue($pagination->isLastPage());
        $this->assertFalse($pagination->hasPreviousPage());
        $this->assertFalse($pagination->hasNextPage());
    }

    public function testConstructorThrowsWhenPageIsZero(): void
    {
        $this->expectException(ValidationException::class);

        new Pagination(0, 20, 0, 0);
    }

    public function testConstructorThrowsWhenPageIsNegative(): void
    {
        $this->expectException(ValidationException::class);

        new Pagination(-1, 20, 0, 1);
    }

    public function testConstructorThrowsWhenPerPageIsZero(): void
    {
        $this->expectException(ValidationException::class);

        new Pagination(1, 0, 0, 0);
    }

    public function testConstructorThrowsWhenTotalIsNegative(): void
    {
        $this->expectException(ValidationException::class);

        new Pagination(1, 20, -1, 0);
    }

    public function testConstructorThrowsWhenTotalPagesIsNegative(): void
    {
        $this->expectException(ValidationException::class);

        new Pagination(1, 20, 0, -1);
    }

    public function testFromArrayThrowsOnMissingKey(): void
    {
        $this->expectException(ValidationException::class);

        Pagination::fromArray(['page' => 1, 'perPage' => 20, 'total' => 0]);
    }

    public function testFromArrayThrowsWhenPageIsNotInt(): void
    {
        $this->expectException(ValidationException::class);

        Pagination::fromArray([
            'page' => '1',
            'perPage' => 20,
            'total' => 0,
            'totalPages' => 0,
        ]);
    }

    public function testFromArrayThrowsWhenPerPageIsNotInt(): void
    {
        $this->expectException(ValidationException::class);

        Pagination::fromArray([
            'page' => 1,
            'perPage' => '20',
            'total' => 0,
            'totalPages' => 0,
        ]);
    }
}

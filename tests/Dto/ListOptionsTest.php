<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Tests\Dto;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Vendor\CommentsClient\Dto\ListOptions;

final class ListOptionsTest extends TestCase
{
    // Значения по умолчанию

    public function testConstructorUsesDefaultValues(): void
    {
        $options = new ListOptions();

        $this->assertSame(1, $options->page);
        $this->assertSame(20, $options->perPage);
    }

    // Явные значения

    public function testConstructorAcceptsExplicitValues(): void
    {
        $options = new ListOptions(3, 50);

        $this->assertSame(3, $options->page);
        $this->assertSame(50, $options->perPage);
    }

    // toArray

    public function testToArrayReturnsBothKeys(): void
    {
        $options = new ListOptions(3, 50);

        $this->assertSame(['page' => 3, 'perPage' => 50], $options->toArray());
    }

    // Нелогичные значения не валидируются на стороне клиента

    #[DataProvider('illogicalValuesProvider')]
    public function testConstructorAcceptsIllogicalValuesWithoutError(int $page, int $perPage): void
    {
        $options = new ListOptions($page, $perPage);

        $this->assertSame($page, $options->page);
        $this->assertSame($perPage, $options->perPage);
    }

    /**
     * @return iterable<string, array{int, int}>
     */
    public static function illogicalValuesProvider(): iterable
    {
        yield 'нулевая страница' => [0, 20];
        yield 'отрицательная страница' => [-5, 20];
        yield 'нулевой размер страницы' => [1, 0];
        yield 'отрицательный размер страницы' => [1, -10];
    }
}

<?php

declare(strict_types=1);

namespace Vendor\CommentsClient\Dto;

use Vendor\CommentsClient\Exception\ValidationException;

/**
 * @implements \IteratorAggregate<int, Comment>
 */
final class CommentsList implements \IteratorAggregate, \Countable
{
    /** @var Comment[] */
    public readonly array $items;

    /**
     * @param Comment[] $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $index => $item) {
            if (!$item instanceof Comment) {
                throw new ValidationException("Поле 'items[{$index}]': ожидается Comment");
            }
        }

        $this->items = $items;
    }

    /**
     * @param array<int, mixed> $items
     */
    public static function fromArray(array $items): self
    {
        $comments = [];
        foreach ($items as $index => $rawComment) {
            if (!is_array($rawComment)) {
                throw new ValidationException("Поле 'items[{$index}]': ожидается массив");
            }

            $comments[] = Comment::fromArray($rawComment);
        }

        return new self($comments);
    }

    /**
     * @return \ArrayIterator<int, Comment>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}

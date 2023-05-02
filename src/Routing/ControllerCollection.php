<?php

declare(strict_types=1);

namespace Phprise\Routing;

use Phprise\Common\Contract\Arrayable;

class ControllerCollection implements \Iterator, \Countable, Arrayable
{
    private int $index = 0;
    private array $array = [];

    public function count(): int
    {
        return count($this->array);
    }

    public function current(): object
    {
        return $this->array[$this->index];
    }

    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function valid(): bool
    {
        return isset($this->array[$this->index]);
    }

    public function add(object ...$controller): void
    {
        array_map(fn ($item) => $this->array[] = $item, $controller);
    }

    public function remove(object ...$controller): void
    {
        array_map(function ($item) {
            unset($this->array[array_search($item, $this->array)]);
        }, $controller);
    }

    public function toArray(): array
    {
        return $this->array;
    }
}

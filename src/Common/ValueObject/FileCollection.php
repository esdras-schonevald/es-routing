<?php

declare(strict_types=1);

namespace Phprise\Common\ValueObject;

use Phprise\Common\Contract\FileCollectionInterface;
use Phprise\Common\Contract\FileInterface;

class FileCollection implements FileCollectionInterface
{
    private int $index = 0;
    private array $array = [];

    public function count(): int
    {
        return count($this->array);
    }

    public function current(): FileInterface
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

    public function add(FileInterface ...$file): void
    {
        array_map(fn ($item) => $this->array[] = $item, $file);
    }

    public function remove(FileInterface ...$file): void
    {
        array_map(function ($item) {
            unset($this->array[array_search($item, $this->array)]);
        }, $file);
    }

    public function toArray(): array
    {
        return $this->array;
    }
}

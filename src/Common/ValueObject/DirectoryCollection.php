<?php

declare(strict_types=1);

namespace Phprise\Common\ValueObject;

use Phprise\Common\Contract\DirectoryCollectionInterface;
use Phprise\Common\Contract\DirectoryInterface;
use Phprise\Common\Contract\FileCollectionInterface;

class DirectoryCollection implements DirectoryCollectionInterface
{
    private int $index = 0;
    private array $array = [];

    public function count(): int
    {
        return count($this->array);
    }

    public function current(): DirectoryInterface
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

    public function toArray(): array
    {
        return $this->array;
    }

    public function add(DirectoryInterface ...$collectible): void
    {
        array_map(fn ($item) => $this->array[] = $item, $collectible);
    }

    public function remove(DirectoryInterface ...$directory): void
    {
        array_map(function ($item) {
            unset($this->array[array_search($item, $this->array)]);
        }, $directory);
    }

    public function getDirectoriesTree(): DirectoryCollectionInterface
    {
        $tree = new DirectoryCollection();
        array_map(fn ($dir) => $tree->add(
            ...$dir->getDirectoriesTree()->toArray()
        ), $this->array);

        return $tree;
    }

    public function getFiles(): FileCollectionInterface
    {
        $files = new FileCollection();
        array_map(fn ($dir) => $files->add(...$dir->getFiles()->toArray()), $this->array);
        return $files;
    }
}

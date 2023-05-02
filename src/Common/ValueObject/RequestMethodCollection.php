<?php

declare(strict_types=1);

namespace Phprise\Common\ValueObject;

use Phprise\Common\Contract\RequestMethodCollectionInterface;
use Phprise\Common\Contract\RequestMethodInterface;

class RequestMethodCollection implements RequestMethodCollectionInterface
{
    private array $array;

    private int $index;

    public function __construct(?RequestMethodInterface ...$requestMethod)
    {
        if (!empty($requestMethod)) {
            $this->add(...$requestMethod);
        }
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function current(): RequestMethodInterface
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

    public function add(RequestMethodInterface ...$requesMethod): void
    {
        array_map(fn ($item) => $this->array[] = $item, $requesMethod);
    }

    public function remove(RequestMethodInterface ...$requestMethod): void
    {
        array_map(function ($item) {
            unset($this->array[array_search($item, $this->array)]);
        }, $requestMethod);
    }

    public function toArray(): array
    {
        return $this->array;
    }
}

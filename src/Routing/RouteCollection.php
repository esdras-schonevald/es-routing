<?php

declare(strict_types=1);

namespace App\Routing;

use App\Routing\RouteInterface;

class RouteCollection implements \Iterator, \Countable
{
    private int $index = 0;
    private array $array = [];

    public function count(): int
    {
        return count($this->array);
    }

    public function current(): RouteInterface
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

    public function add(RouteInterface ...$route): void
    {
        array_map(fn ($item) => $this->array[] = $item, $route);
    }

    public function remove(RouteInterface ...$route): void
    {
        array_map(function ($item) {
            unset($this->array[array_search($item, $this->array)]);
        }, $route);
    }

    public function toArray(): array
    {
        return $this->array;
    }

    public function search(string $attribute, mixed $needle): array
    {
        return array_filter($this->toArray(), function ($route) use ($attribute, $needle) {
            return $route->$attribute === $needle;
        }) ?? [];
    }

    public function match(): RouteInterface
    {
        $filtered = array_filter($this->toArray(), function (RouteInterface $route) {
            return $route->match();
        });

        if (empty($filtered)) {
            throw new \Exception('Route not found');
        }

        return current($filtered);
    }
}

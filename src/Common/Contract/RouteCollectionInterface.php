<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface RouteCollectionInterface extends \Iterator, \Countable, Arrayable
{
    public function current(): RouteInterface;

    public function add(RouteInterface ...$route): void;

    public function remove(RouteInterface ...$route): void;

    public function search(string $attribute, mixed $needle): array;

    public function match(): RouteInterface;
}

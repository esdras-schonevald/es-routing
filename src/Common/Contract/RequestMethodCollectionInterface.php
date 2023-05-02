<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface RequestMethodCollectionInterface extends \Countable, \Iterator, Arrayable
{
    public function current(): RequestMethodInterface;

    public function add(RequestMethodInterface ...$requesMethod): void;

    public function remove(RequestMethodInterface ...$requestMethod): void;
}

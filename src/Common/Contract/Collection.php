<?php

declare(strict_types=1);

namespace App\Common\Contract;

use App\Common\Contract\Arrayable;

interface Collection extends \Iterator, \Countable, Arrayable
{
    function add(Collectible ...$collectible): void;

    function remove(Collectible ...$collectible): void;
}

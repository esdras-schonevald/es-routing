<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

use Phprise\Common\Contract\Arrayable;

interface FileCollectionInterface extends \Iterator, \Countable, Arrayable
{
    function current(): FileInterface;

    function add(FileInterface ...$file): void;

    function remove(FileInterface ...$file): void;
}

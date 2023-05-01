<?php

declare(strict_types=1);

namespace App\Common\Contract;

use App\Common\Contract\Arrayable;
use App\Common\ValueObject\File;

interface FileCollectionInterface extends \Iterator, \Countable, Arrayable
{
    function current(): File;

    function add(File ...$file): void;

    function remove(File ...$file): void;
}

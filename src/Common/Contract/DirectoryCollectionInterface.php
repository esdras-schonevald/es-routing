<?php

declare(strict_types=1);

namespace App\Common\Contract;

use App\Common\ValueObject\Directory;
use App\Common\ValueObject\DirectoryCollection;
use App\Common\ValueObject\FileCollection;

interface DirectoryCollectionInterface extends \Countable, \Iterator, Arrayable
{
    function current(): Directory;

    function add(Directory ...$collectible): void;

    function remove(Directory ...$collectible): void;

    function getDirectoriesTree(): DirectoryCollection;

    function getFiles(): FileCollection;
}

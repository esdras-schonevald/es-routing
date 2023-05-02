<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface DirectoryCollectionInterface extends \Countable, \Iterator, Arrayable
{
    function current(): DirectoryInterface;

    function add(DirectoryInterface ...$collectible): void;

    function remove(DirectoryInterface ...$collectible): void;

    function getDirectoriesTree(): DirectoryCollectionInterface;

    function getFiles(): FileCollectionInterface;
}

<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface DirectoryInterface extends \IteratorAggregate, \Stringable
{

    public static function createFromString(string $directory): self;

    /**
     * Change directory to new relative or absolute path
     * E.g.
     *      $dir = Directory->createFromString(directory: '/var/www/html'); // current dir is '/var/www/html'
     *      $dir->change(path: '../my-project'); // current dir is '/var/www/my-project'
     *      $dir->change(path: 'src'); // current dir is '/var/www/my-project/src'
     *      $dir->change(path: '/usr'); // current dir is '/usr'
     *
     * @param string $path absolute or relative path
     * @return void
     */
    public function change(string $path): void;

    /**
     * Get directories tree
     *
     * @return DirectoryCollectionInterface
     */
    public function getDirectoriesTree(): DirectoryCollectionInterface;

    public function getFilesTree(): FileCollectionInterface;

    public function getFiles(): FileCollectionInterface;
}

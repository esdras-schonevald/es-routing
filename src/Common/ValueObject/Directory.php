<?php

declare(strict_types=1);

namespace App\Common\ValueObject;

use App\Common\Contract\Collectible;
use App\Common\ValueObject\DirectoryCollection;

class Directory implements \Stringable, Collectible, \IteratorAggregate
{

    private function __construct(
        private \Directory $directory,
        private DirectoryCollection $nodeDirectories = new DirectoryCollection(),
        private FileCollection $nodeFiles = new FileCollection()
    ) {
        $nodes = array_filter(
            scandir($this->directory->path),
            fn ($dir) => $dir !== '.' && $dir !== '..'
        );

        array_map(function ($node) {
            $path = $this->directory->path . DIRECTORY_SEPARATOR . $node;
            if (is_dir($path)) {
                $this->nodeDirectories->add(
                    Directory::createFromString($node)
                );
            }

            if (is_file($path)) {
                $this->nodeFiles->add(
                    new File($path, true)
                );
            }
        }, $nodes);
    }

    public static function createFromString(string $directory): self
    {
        return new self(directory: dir($directory));
    }

    public function __toString(): string
    {
        return $this->directory->path;
    }

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
    public function change(string $path): void
    {
        if (!is_dir($this->directory->path)) {
            $this->directory = dir(getcwd());
        }

        $output = shell_exec('cd ' . $this->directory->path . ' && cd ' . $path . ' && pwd');
        if (!is_dir($output)) {
            throw new \InvalidArgumentException('Argument 1 must be a valid path');
        }

        $this->directory = dir($output);
    }

    public function getDirectoriesTree(): DirectoryCollection
    {
        $tree = new DirectoryCollection();
        $tree->add($this);
        $nodes      =   $this->nodeDirectories;
        $nodesTree  =   $nodes->getDirectoriesTree();
        $tree->add(...$nodesTree->toArray());

        return $tree;
    }

    public function getFilesTree(): FileCollection
    {
        $dirs = $this->getDirectoriesTree();
        return $dirs->getFiles();
    }

    public function getFiles(): FileCollection
    {
        return $this->nodeFiles;
    }

    public function getIterator(): \Traversable
    {
        return $this->nodeDirectories;
    }
}

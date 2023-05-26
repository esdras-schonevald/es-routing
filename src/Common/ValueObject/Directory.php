<?php

declare(strict_types=1);

namespace Phprise\Common\ValueObject;

use Phprise\Common\Contract\DirectoryCollectionInterface;
use Phprise\Common\Contract\DirectoryInterface;
use Phprise\Common\Contract\FileCollectionInterface;
use Phprise\Common\ValueObject\DirectoryCollection;

class Directory implements DirectoryInterface
{

    private function __construct(
        private \Directory $directory,
        private DirectoryCollectionInterface $nodeDirectories = new DirectoryCollection(),
        private FileCollectionInterface $nodeFiles = new FileCollection()
    ) {
        $nodes = array_filter(
            scandir($this->directory->path),
            fn ($dir) => $dir !== '.' && $dir !== '..'
        );

        array_map(function ($node) {
            $path = $this->directory->path . DIRECTORY_SEPARATOR . $node;
            if (is_dir($path)) {
                $this->nodeDirectories->add(
                    Directory::createFromString($path)
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

    public function getDirectoriesTree(): DirectoryCollectionInterface
    {
        $tree = new DirectoryCollection();
        $tree->add($this);
        $nodes      =   $this->nodeDirectories;
        $nodesTree  =   $nodes->getDirectoriesTree();
        $tree->add(...$nodesTree->toArray());

        return $tree;
    }

    public function getFilesTree(): FileCollectionInterface
    {
        $dirs = $this->getDirectoriesTree();
        return $dirs->getFiles();
    }

    public function getFiles(): FileCollectionInterface
    {
        return $this->nodeFiles;
    }

    public function getIterator(): \Traversable
    {
        return $this->nodeDirectories;
    }
}

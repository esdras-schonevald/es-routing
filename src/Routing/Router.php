<?php

declare(strict_types=1);

namespace App\Routing;

use App\Hook\PubGet;
use Exception;
use ReflectionClass;

class Router
{
    use PubGet;

    private array $routeCollection = [];

    public function registerController(string $controllerName, bool $registerSubclasses = false): void
    {
        $ref = new ReflectionClass($controllerName);
        $methods = $ref->getMethods();
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();

            foreach ($attributes as $attribute) {
                $name = $attribute->getName();
                $args = $attribute->getArguments();

                if ($name == Route::class) {
                    $this->routeCollection[] = [
                        'path'          =>  $args['path'] ?? $args[0],
                        'method'        =>  $args['method'] ?? $args[1] ?? 'get',
                        'controller'    =>  $controllerName,
                        'action'        =>  $method->getName()
                    ];
                }
            }
        }

        if ($registerSubclasses) {
            $this->registerSubclasses($controllerName);
        }
    }

    public function registerControllers(string ...$controllerNames): void
    {
        foreach ($controllerNames as $controllerName) {
            $this->registerController($controllerName);
        }
    }

    public function registerIncludePath(string $path, ?array $options = null): void
    {
        if (!is_dir($path)) {
            throw new Exception('Path is not a directory ' . $path);
        }

        $files              =   scandir($path);
        $availableFiles     =   $this->filterFilesByOptions($files, $options);
        $declaredClasses    =   get_declared_classes();

        foreach ($availableFiles as $file) {
            $filename = $path . '/' . $file;
            if (is_file($filename)) {
                @include_once($filename);
            } elseif (is_dir($filename)) {
                $this->registerIncludePath($filename, $options);
            }
        }

        $addedClasses = array_diff(get_declared_classes(), $declaredClasses);
        $this->registerControllers(...$addedClasses);
    }

    public function registerIncludePaths(array $paths, ?array $options = null): void
    {
        foreach ($paths as $path) {
            $this->registerIncludePath($path, $options);
        }
    }

    public function execute()
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $matches = array_filter($this->routeCollection, function ($route) use ($url) {
            return $route['path'] == $url;
        });

        foreach ($matches as $match) {
            $controller = new $match['controller'];
            $action     = $match['action'];

            $controller->$action();
        }
    }


    protected function filterFilesByOptions(array $haystack, ?array $options = null): array
    {
        return array_filter($haystack, function (string $file) use ($options) {
            $pos        =   strrpos($file, '.');
            $extension  =   substr($file, $pos + 1);
            $filename   =   substr($file, 0, $pos);

            $opt_ext = $options['extension'] ?? 'php';
            if ($extension !== $opt_ext) {
                return false;
            }

            if (
                isset($options['sufix']) &&
                $options['sufix'] !== substr($filename, -1 * strlen($options['sufix']))
            ) {
                return false;
            }

            if (
                isset($options['prefix']) &&
                $options['prefix'] !== substr($filename, 0, strlen($options['prefix']))
            ) {
                return false;
            }

            return true;
        });
    }

    protected function registerSubclasses(string $className): void
    {
        $classes = get_declared_classes();
        foreach ($classes as $class) {
            if (is_subclass_of($class, $className)) {
                $this->registerController($class);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Phprise\Routing;

use Exception;
use Phprise\Common\Contract\DirectoryInterface;
use Phprise\Common\Contract\RequestMethodCollectionInterface;
use Phprise\Common\Contract\RouteCollectionInterface;
use Phprise\Common\Contract\RouteInterface;
use Phprise\Common\Contract\RoutePathInterface;
use Phprise\Common\Contract\RouterConfigurationInterface;
use Phprise\Common\ValueObject\Directory;
use Phprise\Common\ValueObject\DirectoryCollection;
use Phprise\Common\ValueObject\RequestMethod;
use Phprise\Common\ValueObject\RequestMethodCollection;
use Phprise\Common\ValueObject\RoutePath;
use Phprise\Routing\ControllerCollection;
use Phprise\Routing\Route;
use Phprise\Routing\RouteCollection;

class RouterConfiguration implements RouterConfigurationInterface
{
    private RouteCollectionInterface $routeCollection;
    private ControllerCollection $controllerCollection;
    private DirectoryCollection $directoryCollection;

    public function __construct(
        private ?string $sufix      =   'Controller',
        private ?string $prefix     =   null,
        private string $extension   =   'php'
    ) {
        $this->routeCollection = new RouteCollection();
        $this->controllerCollection = new ControllerCollection();
        $this->directoryCollection = new DirectoryCollection();
    }

    /** public methods */

    public function addRoute(RouteInterface ...$route): void
    {
        $this->routeCollection->add(...$route);
    }

    public function addController(object ...$controller): void
    {
        $this->controllerCollection->add(...$controller);
    }

    public function addDirectory(DirectoryInterface ...$directory): void
    {
        $this->directoryCollection->add(...$directory);
    }

    public function getMatchedRoute(): RouteInterface
    {
        return $this->routeCollection->match();
    }

    /** protected methods */

    protected function registerFromAttributes(): void
    {
        $dirTree            =   $this->directoryCollection->getDirectoriesTree();
        $files              =   $dirTree->getFiles();
        $availableFiles     =   $this->filterFiles($files->toArray());
        $declaredClasses    =   get_declared_classes();
        array_map(fn ($file) => @include_once((string)$file), $availableFiles);
        $addedClasses       =   array_diff(get_declared_classes(), $declaredClasses);
        $objects            =   array_map(fn ($class) => new $class(), $addedClasses);
        $this->registerAttributeControllers(...$objects);
    }

    protected function registerAttributeController(object $controller): void
    {
        $ref        =   new \ReflectionClass($controller);
        $methods    =   $ref->getMethods();

        array_map(
            fn ($method) => $this->addRoutesFromMethod($method, $controller),
            $methods
        );
    }

    protected function addRoutesFromMethod(\ReflectionMethod $method, object $controller): void
    {
        $attributes =   $method->getAttributes();
        $action     =   $method->getName();

        array_map(
            fn ($attribute) => $this->addRouteByAttribute($attribute, $controller, $action),
            $attributes
        );
    }

    protected function addRouteByAttribute(\ReflectionAttribute $attribute, object $controller, string $action): void
    {
        if ($attribute->getName() !== Route::class) {
            return;
        }

        $path       =   $this->getAttributeRoutePath($attribute);
        $methods    =   $this->getAttributeRouteMethods($attribute);
        $route      =   new Route($path, $methods, $controller, $action);

        $this->routeCollection->add($route);
    }

    protected function getAttributeRoutePath(\ReflectionAttribute $attribute): RoutePathInterface
    {
        $args = $attribute->getArguments();
        $path = $args['path'] ?? $args[0];

        if ($path instanceof RoutePathInterface) {
            return $path;
        }

        return new RoutePath((string) $path);
    }

    protected function getAttributeRouteMethods(\ReflectionAttribute $attribute): RequestMethodCollectionInterface
    {
        $args   =   $attribute->getArguments();
        $arg    =   $args['methods'] ?? $args['method'] ?? $args[1] ?? null;

        if ($arg instanceof RequestMethodCollectionInterface) {
            return $arg;
        }

        if (is_array($arg)) {
            return new RequestMethodCollection(
                ...array_map(function ($item) {
                    return RequestMethod::from(strtoupper($item));
                }, $arg)
            );
        }

        if (is_string($arg)) {
            $arg = RequestMethod::from(strtoupper($arg));
        }

        if ($arg instanceof RequestMethod) {
            return new RequestMethodCollection($arg);
        }

        return new RequestMethodCollection(RequestMethod::GET);
    }

    protected function registerAttributeControllers(object ...$controllers): void
    {
        array_map(fn ($controller) => $this->registerAttributeController(new $controller()), $controllers);
    }

    protected function filterFiles(array $files)
    {
        return array_filter($files, function ($file) {
            $pos        =   strrpos((string)$file, '.');
            $extension  =   substr((string)$file, $pos + 1);
            $filename   =   substr((string)$file, 0, $pos);

            if ($this->hasInvalidExtension($extension)) {
                return false;
            }

            if ($this->hasInvalidPrefix($filename)) {
                return false;
            }

            if ($this->hasInvalidSufix($filename)) {
                return false;
            }

            return true;
        });
    }

    protected function hasInvalidExtension(string $extension): bool
    {
        return !empty($this->extension) && $this->extension !== $extension;
    }

    protected function hasInvalidPrefix(string $filename)
    {
        return !empty($this->prefix) &&
            $this->prefix !== substr($filename, 0, strlen($this->prefix));
    }

    protected function hasInvalidSufix(string $filename)
    {
        return !empty($this->sufix) &&
            $this->sufix !== substr($filename, -1 * strlen($this->sufix));
    }

    /** static methods */

    public static function createFromAnnotations(string $pathToControllers): RouterConfigurationInterface
    {
        return new self();
    }

    public static function createFromAttributes(string $pathToControllers): RouterConfigurationInterface
    {
        if (!is_dir($pathToControllers)) {
            throw new Exception('Argument 1 must be a valid directory');
        }

        $config = new self();
        $config->addDirectory(Directory::createFromString($pathToControllers));
        $config->registerFromAttributes();

        return $config;
    }

    public static function createFromIniFile(string $pathToFile): RouterConfigurationInterface
    {
        return new self();
    }

    public static function createFromJsonFile(string $pathToFile): RouterConfigurationInterface
    {
        return new self();
    }

    public static function createFromPhpFile(string $pathToFile): RouterConfigurationInterface
    {
        return new self();
    }

    public static function createFromXmlFile(string $pathToFile): RouterConfigurationInterface
    {
        return new self();
    }

    public static function createFromYamlFile(string $yamlFile): RouterConfigurationInterface
    {
        return new self();
    }

    /** getters and setters */

    public function getControllerSufix(): ?string
    {
        return $this->sufix;
    }

    public function setControllerSufix(?string $sufix): void
    {
        $this->sufix = $sufix;
    }

    public function getControllerPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setControllerPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getControllerExtension(): ?string
    {
        return $this->extension;
    }

    public function setControllerExtension(?string $extension): void
    {
        $this->extension = $extension;
    }
}

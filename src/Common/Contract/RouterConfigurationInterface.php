<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface RouterConfigurationInterface
{
    /** public methods contracts */

    public function addRoute(RouteInterface ...$route): void;

    public function addController(object ...$controller): void;

    public function addDirectory(DirectoryInterface ...$directory): void;

    public function getMatchedRoute(): RouteInterface;

    /** static methods contracts */

    public static function createFromAnnotations(string $pathToControllers): RouterConfigurationInterface;

    public static function createFromAttributes(string $pathToControllers): RouterConfigurationInterface;

    public static function createFromIniFile(string $pathToFile): RouterConfigurationInterface;

    public static function createFromJsonFile(string $pathToFile): RouterConfigurationInterface;

    public static function createFromPhpFile(string $pathToFile): RouterConfigurationInterface;

    public static function createFromXmlFile(string $pathToFile): RouterConfigurationInterface;

    public static function createFromYamlFile(string $yamlFile): RouterConfigurationInterface;

    /** getters and setters contracts */

    public function getControllerSufix(): ?string;

    public function setControllerSufix(?string $sufix): void;

    public function getControllerPrefix(): ?string;

    public function setControllerPrefix(?string $prefix): void;

    public function getControllerExtension(): ?string;

    public function setControllerExtension(?string $extension): void;
}

<?php

declare(strict_types=1);

namespace App\Routing;

class SimpleRouter implements RouterInterface
{
    public function __construct(
        private RouterConfigurationInterface $configuration
    ) {
    }

    public function execute(): void
    {

        $configuration      =   $this->configuration;
        $routeCollection    =   $configuration->routeCollection;
        $route              =   $routeCollection->match();

        $route->use();
    }
}

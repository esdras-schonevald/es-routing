<?php

declare(strict_types=1);

namespace Phprise\Routing;

use Phprise\Common\Contract\RouterConfigurationInterface;
use Phprise\Common\Contract\RouterInterface;

class SimpleRouter implements RouterInterface
{
    public function __construct(
        private RouterConfigurationInterface $configuration
    ) {
    }

    public function execute(): void
    {

        $configuration      =   $this->configuration;
        $route              =   $configuration->getMatchedRoute();

        $route->use();
    }
}

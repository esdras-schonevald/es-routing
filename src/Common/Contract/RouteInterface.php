<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

use Phprise\Common\Contract\RoutePathInterface;
use Phprise\Common\ValueObject\RequestMethodCollection;

interface RouteInterface
{
    public function __construct(RoutePathInterface $path, RequestMethodCollection $methods, object $controller, string $action);

    public function match(): bool;

    public function use(): void;
}

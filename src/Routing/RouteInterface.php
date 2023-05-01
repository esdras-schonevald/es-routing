<?php

declare(strict_types=1);

namespace App\Routing;

use App\Common\ValueObject\Path;
use App\Common\ValueObject\RequestMethodCollection;

interface RouteInterface
{
    public function __construct(Path $path, RequestMethodCollection $methods, object $controller, string $action);

    public function match(): bool;

    public function use(): void;
}

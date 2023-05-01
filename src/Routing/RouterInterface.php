<?php

declare(strict_types=1);

namespace App\Routing;

interface RouterInterface
{
    public function execute(): void;
}

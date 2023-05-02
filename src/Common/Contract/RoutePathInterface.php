<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface RoutePathInterface extends \Stringable
{
    public function __construct(string $path);

    public function getPattern(): string;
}

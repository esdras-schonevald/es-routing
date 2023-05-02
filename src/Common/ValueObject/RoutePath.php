<?php

declare(strict_types=1);

namespace Phprise\Common\ValueObject;

use Phprise\Common\Contract\RoutePathInterface;

class RoutePath implements RoutePathInterface
{
    public function __construct(private string $path)
    {
    }

    public function __toString(): string
    {
        return $this->path;
    }

    public function getPattern(): string
    {
        $pattern = '#^' . preg_replace('/\{([a-zA-Z]+)(:[\w]+)?\}/', '(?P<$1>\w$2+)', (string) $this->path) . '$#i';
        $pattern = str_replace(['\w:int', '\w:integer'], '\d', $pattern);
        $pattern = str_replace(['\w:string', '\w:varchar'], '\w', $pattern);
        $pattern = str_replace(['\w:double', '\w:float', '\w:decimal'], '[\d\,]', $pattern);

        return $pattern;
    }
}

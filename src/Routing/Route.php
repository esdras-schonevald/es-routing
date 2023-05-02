<?php

declare(strict_types=1);

namespace Phprise\Routing;

use Attribute;
use Phprise\Common\Contract\RequestMethodCollectionInterface;
use Phprise\Common\Contract\RouteInterface;
use Phprise\Common\Contract\RoutePathInterface;
use Phprise\Common\ValueObject\RequestMethod;
use Symfony\Component\HttpFoundation\Request;

#[Attribute]
class Route implements RouteInterface
{
    private Request $request;

    public function __construct(
        private RoutePathInterface $path,
        private RequestMethodCollectionInterface $methods,
        private object $controller,
        private string $action
    ) {
        $this->request = Request::createFromGlobals();
    }

    public function match(): bool
    {
        $pattern    =   $this->path->getPattern();
        $path       =   $this->request->getPathInfo();
        $isMatch    =   preg_match($pattern, $path, $matches);

        if (!$isMatch) {
            return false;
        }

        if (!$this->releaseMethod()) {
            return false;
        }

        $parameters = $this->urlMatchesToParamenters($matches);
        $this->setRequestQueryParameters($parameters);

        return true;
    }

    public function use(): void
    {
        $controller =   new $this->controller;
        $action     =   $this->action;

        $controller->$action($this->request);
    }

    private function releaseMethod(): bool
    {
        $request        =   $this->request;
        $server         =   $request->server;
        $requestMethod  =   RequestMethod::from($server->get('REQUEST_METHOD'));
        $methods        =   $this->methods;

        return in_array($requestMethod, $methods->toArray());
    }

    private function setRequestQueryParameters(array $parameters): void
    {
        array_map(function ($key, $value) {
            $this->request->query->set($key, $value);
        }, array_keys($parameters), array_values($parameters));
    }

    private function urlMatchesToParamenters(array $matches): array
    {
        $keys           =   array_keys($matches);
        $stringKeys     =   array_filter($keys, 'is_string');
        $flipped        =   array_flip($stringKeys);
        $intersecteds   =   array_intersect_key($matches, $flipped);

        return $intersecteds;
    }
}

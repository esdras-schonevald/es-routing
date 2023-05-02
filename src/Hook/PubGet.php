<?php

declare(strict_types=1);

namespace Phprise\Hook;

trait PubGet
{
    function __get(string $propertyName)
    {
        $methodName = 'get' . ucfirst($propertyName);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        if (property_exists($this, $propertyName)) {
            return $this->$propertyName;
        }

        throw new \BadMethodCallException();
    }
}

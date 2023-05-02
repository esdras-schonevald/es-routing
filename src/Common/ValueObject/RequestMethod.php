<?php

declare(strict_types=1);

namespace Phprise\Common\ValueObject;

use Phprise\Common\Contract\RequestMethodInterface;

enum RequestMethod: string implements RequestMethodInterface
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
    case OPTIONS = 'OPTIONS';

    public function getMethod(): string
    {
        return $this->name;
    }
}

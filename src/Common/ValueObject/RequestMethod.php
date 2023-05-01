<?php

declare(strict_types=1);

namespace App\Common\ValueObject;

enum RequestMethod: string
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

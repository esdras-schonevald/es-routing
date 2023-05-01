<?php

declare(strict_types=1);

namespace App\Common\Contract;

interface Arrayable
{
    public function toArray(): array;
}

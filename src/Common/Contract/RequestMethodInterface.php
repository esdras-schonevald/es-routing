<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface RequestMethodInterface
{
    public function getMethod(): string;
}

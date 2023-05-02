<?php

declare(strict_types=1);

namespace Phprise\Common\Contract;

interface RouterInterface
{
    public function execute(): void;
}

<?php

declare(strict_types=1);

namespace Phprise\Common\ValueObject;

use Phprise\Common\Contract\FileInterface;

class File extends \Symfony\Component\HttpFoundation\File\File implements FileInterface
{
}

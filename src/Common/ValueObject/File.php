<?php

declare(strict_types=1);

namespace App\Common\ValueObject;

use App\Common\Contract\Collectible;

class File extends \Symfony\Component\HttpFoundation\File\File implements Collectible
{
}

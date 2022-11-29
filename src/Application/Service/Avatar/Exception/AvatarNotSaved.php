<?php

namespace Nurschool\Platform\Application\Service\Avatar\Exception;

use Nurschool\Common\Domain\Exception\Exception;

class AvatarNotSaved extends Exception
{
    static public function create()
    {
        return new self('Avatar can not be saved');
    }
}
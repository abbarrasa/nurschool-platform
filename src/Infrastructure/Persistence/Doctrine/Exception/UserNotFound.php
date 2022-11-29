<?php

namespace Nurschool\Platform\Infrastructure\Persistence\Doctrine\Exception;

use Nurschool\Common\Domain\Exception\Exception;

class UserNotFound extends Exception
{
    public static function fromUuid(string $uuid): self
    {
        return new self(\sprintf('User with id "%s" not found', $uuid));
    }

    public static function fromEmail(string $email): self
    {
        return new self(\sprintf('User with email "%s" not found', $email));
    }
}

<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\Event;

use Nurschool\Shared\Domain\Event\DomainEvent;
use Nurschool\User\Domain\User;
use \Symfony\Contracts\EventDispatcher\Event;

class UserCreated extends Event implements DomainEvent
{
    public const NAME = 'user.created';

    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function eventName(): string
    {
        return self::NAME;
    }
}
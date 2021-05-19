<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Event;


use Nurschool\Shared\Domain\Event\DomainEvent;
use Nurschool\User\Domain\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserAuthenticated extends Event implements DomainEvent
{
    public const NAME = 'user.authenticated';

    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function create(User $user): self
    {
        return new self($user);
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
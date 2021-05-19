<?php


namespace Nurschool\Shared\Infrastructure\Symfony\EventListener;


use Nurschool\Shared\Domain\Event\DomainEventListener;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserCreated;
use Nurschool\Shared\Infrastructure\Symfony\Security\EmailVerifier;

class SendEmailConfirmation implements DomainEventListener
{
    private const CONFIRMATION_ROUTE = 'register_confirmation';

    /** @var EmailVerifier */
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public function __invoke(UserCreated $event): void
    {
        $user = $event->getUser();
        $this->emailVerifier->sendSignedUrl(self::CONFIRMATION_ROUTE, $user);
    }
}
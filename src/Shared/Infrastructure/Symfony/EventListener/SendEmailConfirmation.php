<?php


namespace Nurschool\Shared\Infrastructure\Symfony\EventListener;


use Nurschool\Shared\Domain\Event\DomainEventListener;
use Nurschool\Shared\Infrastructure\Symfony\Security\EmailVerifier;
use Nurschool\User\Domain\Event\UserCreated;
use Nurschool\User\Domain\Model\Repository\UserRepository;

class SendEmailConfirmation implements DomainEventListener
{
    private const CONFIRMATION_ROUTE = 'register_confirmation';

    /** @var EmailVerifier */
    private $emailVerifier;

    private $repository;

    public function __construct(EmailVerifier $emailVerifier, UserRepository $repository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->repository = $repository;
    }

    public function __invoke(UserCreated $event): void
    {
        $id = $event->aggregateId();
        $user = $this->repository->find($id);

        $this->emailVerifier->sendSignedUrl(self::CONFIRMATION_ROUTE, $user);
    }
}
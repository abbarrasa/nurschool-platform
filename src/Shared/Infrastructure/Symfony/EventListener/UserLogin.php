<?php


namespace Nurschool\Shared\Infrastructure\Symfony\EventListener;


use Nurschool\Shared\Domain\Event\DomainEventListener;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserAuthenticated;
use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;

class UserLogin implements DomainEventListener
{
    private $repository;

    private function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UserAuthenticated $event): void
    {
        $user = $event->getUser();
        //Update last login user date

        $this->repository->save($user);
    }
}
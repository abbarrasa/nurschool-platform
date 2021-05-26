<?php


namespace Nurschool\Shared\Infrastructure\Symfony\EventListener;


use Nurschool\User\Domain\Model\Repository\UserRepository;
use Nurschool\User\Infrastructure\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserLogin
{
    private $repository;

    private function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    //App\EventListener\LoginListener:
    //tags:
    //- { name: 'kernel.event_listener', event: 'security.interactive_login' }
    //onSecurityInteractiveLogin
    public function __invoke(InteractiveLoginEvent $event): void
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        //Update last login user date
        $user->updateLastLogin(new \DateTime());

        $this->repository->save($user);
    }
}
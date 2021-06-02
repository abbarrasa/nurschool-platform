<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Shared\Infrastructure\Symfony\EventListener;


use Nurschool\User\Domain\Model\Repository\UserRepository;
use Nurschool\User\Infrastructure\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserLogin
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

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
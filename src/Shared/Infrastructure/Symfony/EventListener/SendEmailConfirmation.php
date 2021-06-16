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


use Nurschool\Shared\Domain\Event\DomainEventListener;
use Nurschool\Shared\Infrastructure\Symfony\Security\EmailVerifier;
use Nurschool\User\Domain\Event\UserCreated;
use Nurschool\User\Domain\Model\Repository\UserRepository;

class SendEmailConfirmation implements DomainEventListener
{
    private const CONFIRMATION_ROUTE = 'register_confirmation';

    /** @var EmailVerifier */
    private $emailVerifier;

    /** @var UserRepository */
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
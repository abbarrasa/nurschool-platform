<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\User\Domain\Model\Event;


use Nurschool\Shared\Application\Event\EventHandlerInterface;
use Nurschool\Shared\Domain\Service\Email\MailerInterface;
use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;

final class UserCreatedHandler implements EventHandlerInterface
{
    private $repository;
    private $mailer;

    public function __construct(UserRepositoryInterface $repository, MailerInterface $mailer)
    {
        $this->repository = $repository;
        $this->mailer = $mailer;
    }

    public function __invoke(UserCreated $event): void
    {
        $user = $this->repository->find($event->getUuid());
        $this->mailer->sendConfirmationEmail($user);
    }
}
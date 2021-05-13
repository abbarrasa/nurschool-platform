<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\EventSubscriber;


use Nurschool\Shared\Domain\Service\Email\MailerInterface;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserCreatedSubscriber implements EventSubscriberInterface
{
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreated::NAME => 'sendEmail'
        ];
    }

    public function sendEmail(UserCreated $event)
    {
        $user = $event->getUser();
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'register_confirm',
            $user->id(),
            $user->email()->toString(),
            ['id' => $user->id()]
        );

        $signedUrl = $signatureComponents->getSignedUrl();
        $expiresAt = $signatureComponents->getExpiresAt();
        $this->mailer->sendConfirmationEmail($user, $signedUrl, $expiresAt);
    }
}
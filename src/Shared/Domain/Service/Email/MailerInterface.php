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

namespace Nurschool\Shared\Domain\Service\Email;


use Nurschool\User\Domain\User;

interface MailerInterface
{
    public function sendConfirmationEmail(User $user, string $confirmationUrl, \DateTimeInterface $expiresAt);
    public function sendResettingPasswordEmail(User $user);

//    public function sendConfirmationEmail(UserInterface $user, VerifyEmailSignatureComponents $signatureComponents/*string $signedUrl, \DateTimeInterface $expiresAt*/);
//    public function sendResettingPasswordEmail(UserInterface $user, ResetPasswordToken $resetToken, int $tokenLifetime);
//    public function sendInvitationEmail(Invitation $invitation, TokenComponents $tokenComponents);
}
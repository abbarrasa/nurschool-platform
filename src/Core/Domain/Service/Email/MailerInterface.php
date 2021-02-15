<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Core\Domain\Service\Email;


use Nurschool\Core\Domain\Model\UserInterface;

interface MailerInterface
{
    public function sendConfirmationEmail(UserInterface $user);
    public function sendResettingPasswordEmail(UserInterface $user);

//    public function sendConfirmationEmail(UserInterface $user, VerifyEmailSignatureComponents $signatureComponents/*string $signedUrl, \DateTimeInterface $expiresAt*/);
//    public function sendResettingPasswordEmail(UserInterface $user, ResetPasswordToken $resetToken, int $tokenLifetime);
//    public function sendInvitationEmail(Invitation $invitation, TokenComponents $tokenComponents);
}
<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Mailer;


use Symfony\Component\Security\Core\User\UserInterface;

interface MailerInterface
{
    public function sendConfirmationEmail(UserInterface $user, string $signedUrl, \DateTimeInterface $expiresAt);
}
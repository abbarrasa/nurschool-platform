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


use FOS\UserBundle\Model\UserInterface;

class SendGridMailer implements MailerInterface
{
    /**
     * @inheritDoc
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        // TODO: Implement sendConfirmationEmailMessage() method.
    }

    /**
     * @inheritDoc
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        // TODO: Implement sendResettingEmailMessage() method.
    }
}
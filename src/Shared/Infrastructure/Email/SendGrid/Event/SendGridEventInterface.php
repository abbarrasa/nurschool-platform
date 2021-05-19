<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Email\SendGrid\Event;


use Nurschool\Shared\Application\Event\DomainEvent;
use SendGrid\Mail\Mail;

interface SendGridEventInterface extends DomainEvent
{
    /**
     * @return Mail
     */
    public function getMail(): Mail;

    /**
     * @param Mail $mail
     */
    public function setMail(Mail $mail);

    /**
     * @return string|null
     */
    public function getMessageId(): ?string;

    /**
     * @param string|null $messageId
     */
    public function setMessageId(?string $messageId);
}
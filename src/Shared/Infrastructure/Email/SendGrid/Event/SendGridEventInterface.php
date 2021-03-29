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


use SendGrid\Mail\Mail;

interface SendGridEventInterface
{
    public const STARTED = 'sendgrid.started';
    public const FAILED = 'sendgrid.failed';
    public const FINISHED = 'sendgrid.finished';

    /**
     * @return Mail
     */
    public function getMail(): Mail;

    /**
     * @param Mail $mail
     * @return $this
     */
    public function setMail(Mail $mail): self;

    /**
     * @return string|null
     */
    public function getMessageId(): ?string;

    /**
     * @param string|null $messageId
     * @return $this
     */
    public function setMessageId(?string $messageId): self;
}
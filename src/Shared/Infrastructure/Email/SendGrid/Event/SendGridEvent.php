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
use Symfony\Contracts\EventDispatcher\Event;

class SendGridEvent extends Event
{
    public const STARTED = 'sendgrid.started';
    public const FAILED = 'sendgrid.failed';
    public const FINISHED = 'sendgrid.finished';

    /** @var Mail */
    private $mail;

    /** @var string */
    private $messageId;

    public function __construct(Mail $mail, ?string $messageId)
    {
        $this->setMail($mail);
        $this->setMessageId($messageId);
    }

    /**
     * @return Mail
     */
    public function getMail(): Mail
    {
        return $this->mail;
    }

    /**
     * @param Mail $mail
     * @return $this
     */
    public function setMail(Mail $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    /**
     * @param string|null $messageId
     * @return $this
     */
    public function setMessageId(?string $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }
}
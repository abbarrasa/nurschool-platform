<?php


namespace Nurschool\Shared\Infrastructure\Email\SendGrid\Logger;


use SendGrid\Mail\Mail;

interface SendGridLoggerInterface
{
    public function logSendingStarted(Mail $mail): void;
    public function logSendingFinished(Mail $mail, ?string $messageId = null): void;
    public function logSendingFailed(Mail $mail): void;

}
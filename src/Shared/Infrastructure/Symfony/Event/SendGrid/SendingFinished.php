<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Event\SendGrid;


final class SendingFinished extends AbstractSendGridEvent
{
    public const NAME = 'sendgrid.finished';

    public function eventName(): string
    {
        return self::NAME;
    }
}
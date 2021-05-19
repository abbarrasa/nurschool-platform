<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Event\SendGrid;


final class SendingStarted extends AbstractSendGridEvent
{
    public const NAME = 'sendgrid.started';

    public function eventName(): string
    {
        return self::NAME;
    }
}
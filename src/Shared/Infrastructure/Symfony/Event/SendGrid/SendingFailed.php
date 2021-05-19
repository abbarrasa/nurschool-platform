<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Event\SendGrid;


final class SendingFailed extends AbstractSendGridEvent
{
    public const NAME = 'sendgrid.failed';

    public function eventName(): string
    {
        return self::NAME;
    }
}
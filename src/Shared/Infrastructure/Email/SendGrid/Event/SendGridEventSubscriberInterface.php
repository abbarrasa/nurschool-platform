<?php


namespace Nurschool\Shared\Infrastructure\Email\SendGrid\Event;


interface SendGridEventSubscriberInterface
{
    public function onStarted(SendGridEventInterface  $event): void;

    public function onFinished(SendGridEventInterface  $event): void;

    public function onFailed(SendGridEventInterface $event): void;
}
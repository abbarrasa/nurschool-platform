<?php


namespace Nurschool\Shared\Infrastructure\Email\SendGrid;


use Nurschool\Shared\Infrastructure\Email\SendGrid\Event\SendGridEventInterface;

interface SendGridEventDispatcherInterface
{
    public function dispatch(SendGridEventInterface $event, string $eventName = null): void;
}
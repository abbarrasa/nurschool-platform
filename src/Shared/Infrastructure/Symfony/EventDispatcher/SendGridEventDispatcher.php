<?php


namespace Nurschool\Shared\Infrastructure\Symfony\EventDispatcher;


use Nurschool\Shared\Infrastructure\Email\SendGrid\SendGridEventDispatcherInterface;
use Nurschool\Shared\Infrastructure\Email\SendGrid\Event\SendGridEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SendGridEventDispatcher implements SendGridEventDispatcherInterface
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(SendGridEventInterface $event, string $eventName = null): void
    {
        $this->dispatcher->dispatch($event, $eventName);
    }
}
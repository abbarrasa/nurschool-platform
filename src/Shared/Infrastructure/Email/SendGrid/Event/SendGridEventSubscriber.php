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


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendGridEventSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SendGridEvent::STARTED => 'onStarted',
            SendGridEvent::FINISHED => 'onFinished',
            SendGridEvent::FAILED => 'onFailed'
        ];
    }

    public function onFailed(SendGridEvent $event): void
    {
    }

    public function onStarted(SendGridEvent $event): void
    {
    }

    public function onFinished(SendGridEvent $event): void
    {
    }
}
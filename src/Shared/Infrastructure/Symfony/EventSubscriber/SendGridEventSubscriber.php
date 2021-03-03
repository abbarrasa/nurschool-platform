<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Infrastructure\Symfony\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendGridEventSubscriber implements SendGridEventSubscriberInterface, EventSubscriberInterface
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

    public function onFailed(SendGridEventInterface $event): void
    {
    }

    public function onStarted(SendGridEventInterface $event): void
    {
    }

    public function onFinished(SendGridEventInterface $event): void
    {
    }
}
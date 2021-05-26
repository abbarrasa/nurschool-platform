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


use Nurschool\Shared\Infrastructure\Email\SendGrid\Event\SendGridEventInterface;
use Nurschool\Shared\Infrastructure\Email\SendGrid\Event\SendGridEventSubscriberInterface;
use Nurschool\Shared\Infrastructure\Email\SendGrid\Logger\SendGridLoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendGridEventSubscriber implements SendGridEventSubscriberInterface, EventSubscriberInterface
{
    /** @var SendGridLoggerInterface */
    private $logger;

    public function __construct(SendGridLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SendGridEventInterface::STARTED => 'onStarted',
            SendGridEventInterface::FINISHED => 'onFinished',
            SendGridEventInterface::FAILED => 'onFailed'
        ];
    }

    public function onFailed(SendGridEventInterface $event): void
    {
        $mail = $event->getMail();
        $this->logger->logSendingFailed($mail);
    }

    public function onStarted(SendGridEventInterface $event): void
    {
    }

    public function onFinished(SendGridEventInterface $event): void
    {
    }
}
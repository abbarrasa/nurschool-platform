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


use Nurschool\Shared\Infrastructure\Email\SendGrid\Logger\SendGridLoggerInterface;
use Nurschool\Shared\Infrastructure\Symfony\Event\SendGrid\SendingFailed;
use Nurschool\Shared\Infrastructure\Symfony\Event\SendGrid\SendingFinished;
use Nurschool\Shared\Infrastructure\Symfony\Event\SendGrid\SendingStarted;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendGridEventSubscriber implements EventSubscriberInterface
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
            SendingStarted::NAME => 'onStarted',
            SendingFinished::FINISHED => 'onFinished',
            SendingFailed::FAILED => 'onFailed'
        ];
    }

    public function onFailed(SendingFailed $event): void
    {
        $mail = $event->getMail();
        $this->logger->logSendingFailed($mail);
    }

    public function onStarted(SendingStarted $event): void
    {
    }

    public function onFinished(SendingFinished $event): void
    {
    }
}
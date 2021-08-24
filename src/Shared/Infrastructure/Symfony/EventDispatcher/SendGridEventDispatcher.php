<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Shared\Infrastructure\Symfony\EventDispatcher;


use Nurschool\Shared\Infrastructure\Email\SendGrid\Event\SendGridEventInterface;
use Nurschool\Shared\Infrastructure\Email\SendGrid\EventDispatcher\SendGridEventDispatcherInterface;
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
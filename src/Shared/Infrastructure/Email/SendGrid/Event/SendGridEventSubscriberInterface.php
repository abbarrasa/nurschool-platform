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


interface SendGridEventSubscriberInterface
{
    public function onFailed(SendGridEventInterface $event): void;

    public function onStarted(SendGridEventInterface $event): void;

    public function onFinished(SendGridEventInterface $event): void;
}
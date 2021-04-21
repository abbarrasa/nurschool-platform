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

namespace Nurschool\Shared\Infrastructure\Symfony\Bus;


use Symfony\Component\Messenger\Exception\HandlerFailedException;

trait MessageBusExceptionTrait
{
    /**
     * @param HandlerFailedException $exception
     * @throws \Throwable
     */
    public function throwException(HandlerFailedException $exception): void
    {
        while($exception instanceof HandlerFailedException) {
            /** @var \Throwable $exception */
            $exception = $exception->getPrevious();
        }

        throw $exception;
    }
}
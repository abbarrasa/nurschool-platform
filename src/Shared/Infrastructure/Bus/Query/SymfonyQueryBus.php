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

namespace Nurschool\Shared\Infrastructure\Bus\Query;


use Nurschool\Shared\Application\Query\QueryBus;
use Nurschool\Shared\Application\Query\Query;
use Nurschool\Shared\Application\Query\Response;
use Nurschool\Shared\Infrastructure\Symfony\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyQueryBus implements QueryBus
{
    use MessageBusExceptionTrait;

    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function ask(Query $query): Response
    {
        try {
            /** @var HandledStamp $stamp */
            $stamp = $this->messageBus->dispatch($query)->last(HandledStamp::class);

            return $stamp->getResult();
        } catch(NoHandlerForMessageException $exception) {
            throw new QueryNotRegisteredException($query);
        } catch(HandlerFailedException $exception) {
            $this->throwException($exception);
        }
    }
}
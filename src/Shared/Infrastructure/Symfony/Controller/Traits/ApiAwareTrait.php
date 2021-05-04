<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Controller\Traits;


use Nurschool\Shared\Application\Command\CommandBusInterface;
use Nurschool\Shared\Application\Command\CommandInterface;
use Nurschool\Shared\Application\Query\QueryBusInterface;
use Nurschool\Shared\Application\Query\QueryInterface;
use Nurschool\Shared\Application\Query\Response;

trait ApiAwareTrait
{
    /** @var CommandBusInterface */
    protected $commandBus;

    /** @var QueryBusInterface */
    protected $queryBus;

    /**
     * @required
     * @param CommandBusInterface $commandBus
     */
    public function setCommandBus(CommandBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @required
     * @param QueryBusInterface $queryBus
     */
    public function setQueryBus(QueryBusInterface $queryBus): void
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param CommandInterface $command
     */
    protected function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }

    /**
     * @param QueryInterface $query
     * @return Response|null
     */
    protected function ask(QueryInterface $query): ?Response
    {
        return $this->queryBus->ask($query);
    }
}
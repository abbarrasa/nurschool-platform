<?php


namespace Nurschool\Shared\Infrastructure\Symfony\Controller\Traits;


use Nurschool\Shared\Application\Command\CommandBus;
use Nurschool\Shared\Application\Command\Command;
use Nurschool\Shared\Application\Query\QueryBus;
use Nurschool\Shared\Application\Query\Query;
use Nurschool\Shared\Application\Query\Response;

trait ApiAwareTrait
{
    /** @var CommandBus */
    protected $commandBus;

    /** @var QueryBus */
    protected $queryBus;

    /**
     * @required
     * @param CommandBus $commandBus
     */
    public function setCommandBus(CommandBus $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @required
     * @param QueryBus $queryBus
     */
    public function setQueryBus(QueryBus $queryBus): void
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param Command $command
     */
    protected function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }

    /**
     * @param Query $query
     * @return Response|null
     */
    protected function ask(Query $query): ?Response
    {
        return $this->queryBus->ask($query);
    }
}
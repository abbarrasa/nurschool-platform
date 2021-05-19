<?php


namespace Nurschool\User\Application\Command\Auth;


use Nurschool\Shared\Application\Command\CommandHandler;
use Nurschool\Shared\Domain\Event\DomainEventDispatcher;
use Nurschool\Shared\Infrastructure\Symfony\Event\UserAuthenticated;
use Nurschool\User\Domain\Model\Repository\UserRepositoryInterface;
use Nurschool\User\Domain\User;

final class AuthUserCommandHandler implements CommandHandler
{
    private $repository;
    private $eventDispatcher;

    public function __construct(UserRepositoryInterface $repository, DomainEventDispatcher $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(AuthUserCommand $command): void
    {
        $user = $this->repository->findByEmail($command->email);
        $this->ensureCredentialsAreValid($user, $command->plainPassword);

        $event = UserAuthenticated::create($user);
        $this->eventDispatcher->dispatch($event);
    }

    private function ensureCredentialsAreValid(User $user, string $plainPassword): void
    {
        if (null === $user ||
            !$user->password()->match($plainPassword)
        ) {
            throw new \Exception("Bad credentials");
        }
    }
}
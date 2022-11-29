<?php

namespace Nurschool\Platform\Application\Specification;

use Nurschool\Platform\Domain\Exception\EmailAlreadyExists;
use Nurschool\Platform\Domain\Repository\UserQueryRepository;
use Nurschool\Platform\Domain\Specification\UniqueEmail;
use Nurschool\Platform\Domain\ValueObject\Email;

final class UserUniqueEmail implements UniqueEmail
{
    private $userQueryRepository;

    public function __construct(UserQueryRepository $userQueryRepository)
    {
        $this->userQueryRepository = $userQueryRepository;
    }

    public function isSatisfiedBy(Email $email): bool
    {
        if ($this->userQueryRepository->existsWithEmail($email)) {
            throw EmailAlreadyExists::createFromEmail($email);
        }

        return true;
    }
}

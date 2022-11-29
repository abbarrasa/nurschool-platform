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

namespace Nurschool\Platform\Infrastructure\Symfony\Security\Core;

use Nurschool\Platform\Domain\Repository\UserQueryRepository;
use Nurschool\Platform\Domain\ValueObject\Email;
use Nurschool\Platform\Infrastructure\Persistence\Doctrine\Exception\UserNotFound;
use Nurschool\Platform\Infrastructure\Symfony\Model\UserSecurity;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserQueryRepository $userQueryRepository;

    public function __construct(UserQueryRepository $userQueryRepository)
    {
        $this->userQueryRepository = $userQueryRepository;
    }

    public function refreshUser(UserInterface $user)
    {

    }

    public function supportsClass(string $class)
    {

    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $email = Email::fromString($identifier);        
        $user = $this->userQueryRepository->findByEmailOrFail($email);

        return new UserSecurity($user);
    }
}
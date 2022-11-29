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

namespace Nurschool\Platform\Application\Command\Create;

use Nurschool\Common\Application\Command\CommandHandler;
use Nurschool\Platform\Application\Service\Avatar\AvatarGenerator;
use Nurschool\Platform\Application\Specification\UserUniqueEmail;
use Nurschool\Platform\Domain\Repository\UserStoreRepository;
use Nurschool\Platform\Domain\User;
use Nurschool\Platform\Domain\ValueObject\Avatar;
use Nurschool\Platform\Domain\ValueObject\Email;
use Nurschool\Platform\Domain\ValueObject\FullName;
use Nurschool\Platform\Domain\ValueObject\GoogleId;

final class CreateUserCommandHandler implements CommandHandler
{
    private UserStoreRepository $userStoreRepository;
    private UserUniqueEmail $userUniqueEmail;
    private AvatarGenerator $avatarGenerator;

    public function __construct(
        UserStoreRepository $userStoreRepository,
        UserUniqueEmail $userUniqueEmail,
        AvatarGenerator $avatarGenerator
    ) {
        $this->userStoreRepository = $userStoreRepository;
        $this->userUniqueEmail = $userUniqueEmail;
        $this->avatarGenerator = $avatarGenerator;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $email = Email::fromString($command->email);
        $fullName = new FullName($command->firstname, $command->lastname);
        if ($command instanceof CreateGoogleUserCommand) {
            $googleId = new GoogleId($command->googleId);
            $avatar = empty($command->image) ?: new Avatar($command->image); 
            $user = User::createFromGoogleId($email, $googleId, $fullName, $avatar, $this->userUniqueEmail);
        } else {
            $user = User::createFromEmail($email, $fullName, $this->userUniqueEmail);
            $avatar = $this->avatarGenerator->generateUserAvatar($user);
            $user->updateAvatar($avatar);
        }

        $this->userStoreRepository->save($user);
    }
}

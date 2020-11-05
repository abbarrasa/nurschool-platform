<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Generator\AvatarGeneratorInterface;
use Nurschool\Model\UserInterface;
use Nurschool\File\Util\FileNameTrait;
use YoHang88\LetterAvatar\LetterAvatar;

class AvatarManager
{
    use FileNameTrait;

    /** @var AvatarGeneratorInterface */
    private $avatarGenerator;

    /** @var string */
    private $uriPrefix;

    /** @var string */
    private $destinationPath;

    /** @var UserManager */
    private $userManager;

    /**
     * AvatarManager constructor.
     * @param AvatarGeneratorInterface $avatarGenerator
     * @param UserManager $userManager
     * @param string $uriPrefix
     * @param string $destinationPath
     */
    public function __construct(AvatarGeneratorInterface $avatarGenerator, UserManager $userManager, string $uriPrefix, string $destinationPath)
    {
        $this->avatarGenerator = $avatarGenerator;
        $this->uriPrefix = $uriPrefix;
        $this->destinationPath = $destinationPath;
        $this->userManager = $userManager;
    }

    /**
     * Generate user avatar using name initials letter.
     * @param UserInterface $user
     * @param bool $saveAndFlush
     */
    public function setInitialAvatar(UserInterface $user, bool $saveAndFlush = false): void
    {
        $fullname = $user->getFullName();
        if (empty($fullname)) {
            $fullname = $user->getEmail();
        }

        $user->setAvatar($this->avatarGenerator->createAvatar($this->destinationPath, $fullname));

        if ($saveAndFlush) {
            $this->userManager->save($user);
        }
    }

    /**
     * Update user avatar with image from uri
     * @param string $uri
     * @param UserInterface $user
     * @param bool $saveAndFlush
     */
    public function setAvatarFromUri(string $uri, UserInterface $user, bool $saveAndFlush = false): void
    {
        $user->setAvatar($this->avatarGenerator->createAvatarFromFile($this->destinationPath, $uri));
        if ($saveAndFlush) {
            $this->userManager->save($user);
        }
    }

    /**
     * Get user avatar url
     * @param UserInterface $user
     * @return string|null
     */
    public function getAvatarUrl(UserInterface $user): ?string
    {
        if (null === ($avatar = $user->getAvatar())) {
            return null;
        }

        return \sprintf('%s/%s', $this->uriPrefix, $avatar);
    }
}
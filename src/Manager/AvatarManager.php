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
use Nurschool\Model\UserInterface;
use Nurschool\File\Util\FileNameTrait;
use YoHang88\LetterAvatar\LetterAvatar;

class AvatarManager
{
    use FileNameTrait;

    /** @var string */
    private $uriPrefix;

    /** @var string */
    private $detinationPath;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AvatarGenerator constructor.
     * @param string $uriPrefix
     * @param string $detinationPath
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(string $uriPrefix, string $detinationPath, EntityManagerInterface $entityManager)
    {
        $this->uriPrefix = $uriPrefix;
        $this->detinationPath = $detinationPath;
        $this->entityManager = $entityManager;
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

        // Square Shape, Size 64px
        $avatar = new LetterAvatar($fullname, 'square', 64);

        $filename = $this->getUniqueFileName();
        // Save Image As JPEG
        if (!$avatar->saveAs(\sprintf('%s/%s', $this->detinationPath, $filename), LetterAvatar::MIME_TYPE_JPEG)) {
            throw new \RuntimeException('Avatar could not be saved');
        }

        $user->setAvatar($filename);

        if ($saveAndFlush) {
            $this->saveAndFlush($user);
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
        $filename = $this->getUniqueFileName();
        \file_put_contents(\sprintf('%s/%s', $this->detinationPath, $filename), \file_get_contents($uri));

        $user->setAvatar($filename);

        if ($saveAndFlush) {
            $this->saveAndFlush($user);
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

    private function saveAndFlush($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
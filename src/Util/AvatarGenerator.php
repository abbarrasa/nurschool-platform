<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Util;


use Doctrine\ORM\EntityManagerInterface;
use Nurschool\Model\UserInterface;
use YoHang88\LetterAvatar\LetterAvatar;

class AvatarGenerator
{
    /** @var string */
    private $path;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(string $path, EntityManagerInterface $entityManager)
    {
        $this->path = $path;
        $this->entityManager = $entityManager;
    }

    /**
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

        // Save Image As PNG/JPEG
        $filename = \str_replace('.', '', \uniqid('', true)) . '.jpg';

        if (!$avatar->saveAs(sprintf('%s/%s', $this->path, $filename), LetterAvatar::MIME_TYPE_JPEG)) {
            throw new \RuntimeException('Avatar could not be saved');
        }

        $user->setAvatar($filename);

        if ($saveAndFlush) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}
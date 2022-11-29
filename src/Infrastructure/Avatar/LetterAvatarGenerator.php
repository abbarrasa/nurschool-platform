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

namespace Nurschool\Platform\Infrastructure\Avatar;

use Nurschool\Platform\Application\Service\Avatar\AvatarGenerator;
use Nurschool\Platform\Application\Service\Avatar\Exception\AvatarNotSaved;
use Nurschool\Platform\Application\Service\Storage\FileStorage;
use Nurschool\Platform\Domain\User;
use Nurschool\Platform\Domain\ValueObject\Avatar;
use YoHang88\LetterAvatar\LetterAvatar;

final class LetterAvatarGenerator implements AvatarGenerator
{
    private const DIR_AVATAR = '/uploads/avatar/';

    private FileStorage $fileStorage;

    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    public function generateUserAvatar(User $user, array $options = []): Avatar
    {
        // Square shape, size 64px
        $avatar = new LetterAvatar((string)$user->fullName(), 'square', 64);

        $tmpFilename = \tempnam(\sys_get_temp_dir(), 'avatar_');

        // Save image as JPEG
        if (!$avatar->saveAs($tmpFilename, LetterAvatar::MIME_TYPE_JPEG)) {
            throw AvatarNotSaved::create();
        }
        
        $filename = (string)$user->id() . '.jpg';
        $path = $this->fileStorage->moveFile($tmpFilename, $filename, self::DIR_AVATAR);

        return new Avatar($path);
    }

 /*   public function createAvatarFromFile(string $destinationPath, $file)
    {
        $filename = $this->getUniqueFileName();
        \file_put_contents(\sprintf('%s/%s', $destinationPath, $filename), \file_get_contents($file));

        return $filename;
    }*/
}
<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Generator;


use Nurschool\File\Util\FileNameTrait;
use YoHang88\LetterAvatar\LetterAvatar;

class DefaultAvatarGenerator implements AvatarGeneratorInterface
{
    use FileNameTrait;

    public function createAvatar(string $destinationPath, $data = null, array $options = [])
    {
        // Square Shape, Size 64px
        $avatar = new LetterAvatar($data, 'square', 64);

        $filename = $this->getUniqueFileName();
        // Save Image As JPEG
        if (!$avatar->saveAs(\sprintf('%s/%s', $destinationPath, $filename), LetterAvatar::MIME_TYPE_JPEG)) {
            throw new \RuntimeException('Avatar could not be saved');
        }

        return $filename;
    }

    public function createAvatarFromFile(string $destinationPath, $file)
    {
        $filename = $this->getUniqueFileName();
        \file_put_contents(\sprintf('%s/%s', $destinationPath, $filename), \file_get_contents($file));

        return $filename;
    }
}
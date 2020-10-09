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


interface AvatarGeneratorInterface
{
    public function createAvatar(string $destinationPath, $data = null, array $options = []);

    public function createAvatarFromFile(string $destinationPath, $file);
}
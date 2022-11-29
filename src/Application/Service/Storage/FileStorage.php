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

namespace Nurschool\Platform\Application\Service\Storage;

interface FileStorage
{
    public function getPublicUrl(string $path): string;

    public function moveFile(string $source, string $filename, string $directory): string;

}
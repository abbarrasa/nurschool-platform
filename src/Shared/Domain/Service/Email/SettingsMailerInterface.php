<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Shared\Domain\Service\Email;


interface SettingsMailerInterface
{
    public function loadSettings(array $settings);

    public function setSetting(string $name, $value);

    public function getSetting(string $name);
}
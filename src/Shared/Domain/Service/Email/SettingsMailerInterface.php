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

namespace Nurschool\Shared\Domain\Service\Email;


interface SettingsMailerInterface
{
    /**
     * Loads mailer settings
     * @param array $settings
     * @return mixed
     */
    public function loadSettings(array $settings);

    /**
     * Sets a mailer setting value
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function setSetting(string $name, $value);

    /**
     * Gets a mailer setting value by its name
     * @param string $name
     * @return mixed
     */
    public function getSetting(string $name);

    /**
     * Gets all mailer settings values
     * @return mixed
     */
    public function getAllSettings();
}
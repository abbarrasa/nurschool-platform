<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Core\Domain\Service\Email;


interface ConfigurationMailerInterface
{
    public function loadConfiguration(array $settings);

    public function getVariable(string $name);
}
<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\Core\Infrastructure\Email;


use Nurschool\Core\Domain\Service\Email\ConfigurationMailerInterface;

class ConfigurationMailer implements ConfigurationMailerInterface
{
    public function loadConfiguration(array $settings)
    {
        // TODO: Implement loadConfiguration() method.
    }

    public function getVariable(string $name)
    {
        // TODO: Implement getVariable() method.
    }
}
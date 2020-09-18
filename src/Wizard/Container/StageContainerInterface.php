<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based in parts of the Zikula package <https://ziku.la/>
 */

namespace Nurschool\Wizard\Container;

use Nurschool\Wizard\Stage\StageInterface;

interface StageContainerInterface
{
    public function get(string $id): ?StageInterface;

    public function add(StageInterface $stage): void;

    public function has(string $id): bool;

    public function all(): array;
}

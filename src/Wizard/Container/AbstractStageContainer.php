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

abstract class AbstractStageContainer implements StageContainerInterface
{
    private $stages;

    /**
     * @param StageInterface[] $stages
     */
    public function __construct(iterable $stages = [])
    {
        $this->stages = [];
        foreach ($stages as $stage) {
            $this->add($stage);
        }
    }

    public function add(StageInterface $stage): void
    {
        $this->stages[get_class($stage)] = $stage;
    }

    public function get(string $id): ?StageInterface
    {
        return $this->stages[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return isset($this->stages[$id]);
    }

    public function all(): array
    {
        return $this->stages;
    }
}

<?php

namespace Nurschool\Wizard\Container;

use Nurschool\Wizard\Stage\StageInterface;

interface StageContainerInterface
{
    public function get(string $id): ?StageInterface;

    public function add(StageInterface $stage): void;

    public function has(string $id): bool;

    public function all(): array;
}

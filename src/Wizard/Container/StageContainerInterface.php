<?php

namespace Nurschool\Wizard\Container;

interface StageContainerInterface
{
    public function add(StageInterface $stage): void;

    public function get(string $id): ?StageInterface;

    public function has(string $id): bool;

    public function all(): array;
}

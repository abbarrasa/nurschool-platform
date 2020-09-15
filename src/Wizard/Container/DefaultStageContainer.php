<?php

namespace Nurschool\Wizard\Container;

class DefaultStageContainer implements StageContainerInterface
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

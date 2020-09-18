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

namespace Nurschool\Wizard;

use InvalidArgumentException;
use Nurschool\Wizard\Container\StageContainerInterface;
use Nurschool\Wizard\Exception\AbortStageException;
use Nurschool\Wizard\Loader\YamlFileLoader;
use Nurschool\Wizard\Stage\StageInterface;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class Wizard
 */
class Wizard
{
    /**
     * @var StageContainerInterface
     */
    private $stageContainer;

    /**
     * @var array
     */
    private $stagesByName = [];

    /**
     * @var array
     */
    private $stageOrder = [];

    /**
     * @var string
     */
    private $defaultStage;

    /**
     * @var string
     */
    private $currentStageName;

    /**
     * @var string
     */
    private $warning = '';

    /**
     * Wizard constructor.
     * @param StageContainerInterface $stageContainer
     * @param string $path
     * @throws LoaderLoadException
     */
    public function __construct(StageContainerInterface $stageContainer, string $path)
    {
        $this->stageContainer = $stageContainer;
        if (empty($path)) {
            throw new LoaderLoadException('No stage definition file provided.');
        }

        $this->loadStagesFromYaml($path);
    }

    /**
     * Load the stage definitions from $path
     *
     * @param string $path
     * @throws \Exception
     */
    public function loadStagesFromYaml(string $path): void
    {
        $pathInfo = pathinfo($path);
        $loader = new YamlFileLoader(new FileLocator($pathInfo['dirname']));
        $stages = $loader->load($pathInfo['basename']);
        foreach ($stages['stages'] as $key => $stageArray) {
            $this->stagesByName[$key] = $stageArray['class'];
            $this->stageOrder[$stageArray['order']] = $key;
            if (isset($stageArray['default'])) {
                $this->defaultStage = $key;
            }
        }
    }

    /**
     * Get the stage that is the first necessary stage
     *
     * @param string $name
     * @return StageInterface
     */
    public function getCurrentStage(string $name): StageInterface
    {
        // compute the stageClass from Request parameter
        $stageClass = $this->getStageClassName($name);

        // loop each stage until finds the first that is necessary
        do {
            $useCurrentStage = false;
            /** @var StageInterface $currentStage */
            if (!isset($currentStage)) {
                $currentStage = $this->getStage($stageClass);
            }
            $this->currentStageName = $currentStage->getName();
            try {
                $isNecessary = $currentStage->isNecessary();
            } catch (AbortStageException $e) {
                $this->warning = $e->getMessage();
                $isNecessary = true;
            }
            if ($isNecessary) {
                $useCurrentStage = true;
            } else {
                $currentStage = $this->getNextStage();
            }
        } while (false === $useCurrentStage);

        return $currentStage;
    }

    /**
     * Get an instance of the previous stage
     *
     * @return StageInterface
     */
    public function getPreviousStage(): StageInterface
    {
        return $this->getSequentialStage('prev');
    }

    /**
     * Get an instance of the next stage
     *
     * @return StageInterface
     */
    public function getNextStage(): StageInterface
    {
        return $this->getSequentialStage('next');
    }

    /**
     * Get either previous or next stage
     *
     * @param string $direction
     * @return StageInterface|null
     */
    private function getSequentialStage(string $direction): ?StageInterface
    {
        $dir = in_array($direction, ['prev', 'next']) ? $direction : 'next';
        ksort($this->stageOrder);
        // forward the array pointer to the current index
        while (current($this->stageOrder) !== $this->currentStageName && null !== key($this->stageOrder)) {
            next($this->stageOrder);
        }
        $key = $dir($this->stageOrder);
        if (null !== $key && false !== $key) {
            return $this->getStage($this->stagesByName[$key]);
        }

        return null;
    }

    /**
     * Get stage from stageContainer
     *
     * @param string $stageClass
     * @return StageInterface
     */
    private function getStage(string $stageClass): StageInterface
    {
        if ($this->stageContainer->has($stageClass)) {
            return $this->stageContainer->get($stageClass);
        }
        throw new FileNotFoundException('Error: Could not find requested stage class.');
    }

    /**
     * Has the wizard been halted?
     *
     * @return bool
     */
    public function isHalted(): bool
    {
        return !empty($this->warning);
    }

    /**
     * Get any warning currently set
     *
     * @return string
     */
    public function getWarning(): string
    {
        return 'WARNING: The Wizard was halted for the following reason. This must be corrected before you can continue. ' . $this->warning;
    }

    /**
     * Match the stage and return the stage classname or default.
     *
     * @param string $name
     * @return string
     * @throws InvalidArgumentException
     */
    private function getStageClassName(string $name): string
    {
        if (!empty($this->stagesByName[$name])) {
            return $this->stagesByName[$name];
        }
        if (!empty($this->defaultStage) && !empty($this->stagesByName[$this->defaultStage])) {
            return $this->stagesByName[$this->defaultStage];
        }
        throw new InvalidArgumentException('The request stage could not be found and there is no default stage defined.');
    }
}
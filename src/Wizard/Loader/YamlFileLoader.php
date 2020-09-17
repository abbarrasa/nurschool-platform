<?php

namespace Nurschool\Wizard\Loader;


use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * YamlFileLoader loads YAML files.
 */
class YamlFileLoader extends FileLoader
{
    private $yamlParser;

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);

        return $this->loadFile($path);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) &&
            in_array(pathinfo($resource, PATHINFO_EXTENSION), ['yml', 'yaml'])
        ;
    }

    /**
     * Loads a YAML file.
     *
     * @throws InvalidArgumentException when the given file is not a local file or when it does not exist
     */
    private function loadFile(string $file): array
    {
        if (!stream_is_local($file)) {
            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $file));
        }

        if (!file_exists($file)) {
            throw new InvalidArgumentException(sprintf('The service file "%s" is not valid.', $file));
        }

        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        return $this->validate($this->yamlParser->parse(file_get_contents($file)), $file);
    }

    /**
     * Validates a YAML file.
     *
     * @param mixed $content
     * @return array
     *
     * @throws InvalidArgumentException When service file is not valid
     */
    private function validate($content, string $file): array
    {
        if (null === $content) {
            return $content;
        }

        if (!is_array($content)) {
            throw new InvalidArgumentException(sprintf('The yaml file "%s" is not valid. It should contain an array. Check your YAML syntax.', $file));
        }

        if (isset($content['stages']) && !is_array($content['stages'])) {
            throw new InvalidArgumentException(sprintf('The "stages" key should contain an array in %s. Check your YAML syntax.', $file));
        }

        return $content;
    }
}


//use Frontend\MainBundle\Component\Cache\Redis\ClientList;
//use Nurschool\Wizard\Container\StageContainerInterface;
//use Symfony\Component\Config\FileLocatorInterface;
//use Symfony\Component\DependencyInjection\ContainerBuilder;
//use Symfony\Component\DependencyInjection\Definition;
//use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
//use Symfony\Component\DependencyInjection\Loader\FileLoader;
//use Symfony\Component\DependencyInjection\Reference;
//use Symfony\Component\Yaml\Parser as YamlParser;
//
///**
// * YamlFileLoader loads YAML files.
// */
//class YamlFileLoader extends FileLoader
//{
//    private $yamlParser;
//    private $stageContainer;
//
//    /**
//     * YamlFileLoader constructor.
//     * @param $stageContainer
//     * @param ContainerBuilder $container
//     * @param FileLocatorInterface $locator
//     */
//    public function __construct($stageContainer, ContainerBuilder $container, FileLocatorInterface $locator)
//    {
//        parent::__construct($container, $locator);
//        $this->stageContainer = $stageContainer;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function load($resource, $type = null)
//    {
//        $path = $this->locator->locate($resource);
//
//        $content = $this->loadFile($path);
//
//        $id = $this->stageContainer instanceof StageContainerInterface ?
//            get_class($this->stageContainer) : $this->stageContainer
//        ;
//        $this->container->register($id);
//        $definition = new Definition($id);
//        $this->container->setDefinition($id, $definition);
//
////        $definition = $this->container->getDefinition();
//        foreach ($content['stages'] as $value) {
//            $definition->addMethodCall('add', [new Reference($value['class'])]);
//        }
//
//        return $content;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function supports($resource, $type = null)
//    {
//        return is_string($resource) &&
//            in_array(pathinfo($resource, PATHINFO_EXTENSION), ['yml', 'yaml'])
//        ;
//    }
//
//    /**
//     * Loads a YAML file.
//     *
//     * @throws InvalidArgumentException when the given file is not a local file or when it does not exist
//     */
//    private function loadFile(string $file): array
//    {
//        if (!stream_is_local($file)) {
//            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $file));
//        }
//
//        if (!file_exists($file)) {
//            throw new InvalidArgumentException(sprintf('The service file "%s" is not valid.', $file));
//        }
//
//        if (null === $this->yamlParser) {
//            $this->yamlParser = new YamlParser();
//        }
//
//        return $this->validate($this->yamlParser->parse(file_get_contents($file)), $file);
//    }
//
//    /**
//     * Validates a YAML file.
//     *
//     * @param mixed $content
//     * @return array
//     *
//     * @throws InvalidArgumentException When service file is not valid
//     */
//    private function validate($content, string $file): array
//    {
//        if (null === $content) {
//            return $content;
//        }
//
//        if (!is_array($content)) {
//            throw new InvalidArgumentException(sprintf('The yaml file "%s" is not valid. It should contain an array. Check your YAML syntax.', $file));
//        }
//
//        if (isset($content['stages']) && !is_array($content['stages'])) {
//            throw new InvalidArgumentException(sprintf('The "stages" key should contain an array in %s. Check your YAML syntax.', $file));
//        }
//
//        return $content;
//    }
//}

//use Symfony\Component\Config\Exception\LoaderLoadException;
//use Symfony\Component\Config\FileLocator;
//use Symfony\Component\Config\FileLocatorInterface;
//use Symfony\Component\Config\Loader\FileLoader;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
//use Symfony\Component\Yaml\Parser as YamlParser;
//
///**
// * YamlFileLoader loads YAML files.
// */
//class YamlFileLoader extends FileLoader
//{
//    private $yamlParser;
//    private $container;
//
//    /**
//     * @required
//     * @param ContainerInterface $container
//     */
//    public function setContainer(ContainerInterface $container)
//    {
//        $this->container = $container;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function load($resource, $type = null)
//    {
//        $path = $this->locator->locate($resource);
//
//        $content = $this->loadFile($path);
//
//        $this->container->fileExists($path);
//
//        // empty file
//        if (null === $content) {
//            return;
//        }
//
//        $this->resolveServices($content);
//
//        return $content;
////
////        foreach ($content as $key => $stageArray) {
////            $this->stagesByName[$key] = $this->container->get($stageArray['class']);
////            $this->stageOrder[$stageArray['order']] = $key;
////            if (isset($stageArray['default'])) {
////                $this->defaultStage = $key;
////            }
////        }
////
////
////
////        if (!file_exists($path)) {
////            throw new LoaderLoadException('Stage definition file cannot be found.');
////        }
////        $pathInfo = pathinfo($path);
////        if (!in_array($pathInfo['extension'], ['yml', 'yaml'])) {
////            throw new LoaderLoadException('Stage definition file must include .yml extension.');
////        }
////
////        // empty the stages
////        $this->stagesByName = [];
////        if (!isset($this->yamlFileLoader)) {
////            $this->yamlFileLoader = new YamlFileLoader(new FileLocator($pathInfo['dirname']));
////        }
////        $this->yamlFileLoader->load($pathInfo['basename']);
////        $stages = $this->yamlFileLoader->getContent();
////        $stages = $stages['stages'];
////        foreach ($stages as $key => $stageArray) {
////            $this->stagesByName[$key] = $this->container->get($stageArray['class']);
////            $this->stageOrder[$stageArray['order']] = $key;
////            if (isset($stageArray['default'])) {
////                $this->defaultStage = $key;
////            }
////        }
////
////
////
////
////
////        $this->content = $this->loadFile($path);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function supports($resource, $type = null)
//    {
//        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
//    }
//
//    /**
//     * Loads a YAML file.
//     *
//     * @throws InvalidArgumentException when the given file is not a local file or when it does not exist
//     */
//    private function loadFile(string $file): array
//    {
//        if (!stream_is_local($file)) {
//            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $file));
//        }
//
//        if (!file_exists($file)) {
//            throw new InvalidArgumentException(sprintf('The service file "%s" is not valid.', $file));
//        }
//
//        if (null === $this->yamlParser) {
//            $this->yamlParser = new YamlParser();
//        }
//
//        return $this->validate($this->yamlParser->parse(file_get_contents($file)), $file);
//    }
//
//    /**
//     * Validates a YAML file.
//     *
//     * @param mixed $content
//     * @return array
//     *
//     * @throws InvalidArgumentException When service file is not valid
//     */
//    private function validate($content, string $file): array
//    {
//        if (null === $content) {
//            return $content;
//        }
//
//        if (!is_array($content)) {
//            throw new InvalidArgumentException(sprintf('The yaml file "%s" is not valid. It should contain an array. Check your YAML syntax.', $file));
//        }
//
//        if (isset($content['stages']) && !is_array($content['stages'])) {
//            throw new InvalidArgumentException(sprintf('The "stages" key should contain an array in %s. Check your YAML syntax.', $file));
//        }
//
//        return $content;
//    }
//
//    private function resolveServices($content): void
//    {
//        if (isset($content['stages'])) {
//            $content = $content['stages'];
//        }
//
//        array_walk(isset($content['stages']) ? $content['stages'] : $content,
//            function($value) {
//                $value['class'] = $this->container->get($value['class']);
//            }
//        );
//    }
//}

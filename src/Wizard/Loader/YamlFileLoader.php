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

namespace Nurschool\Wizard\Loader;


use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\Loader\FileLoader;
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
     * @param string $file
     * @return array
     * @throws LoaderLoadException when the given file is not a local file or when it does not exist
     */
    private function loadFile(string $file): array
    {
        if (!stream_is_local($file)) {
            throw new LoaderLoadException(sprintf('This is not a local file "%s".', $file));
        }

        if (!file_exists($file)) {
            throw new LoaderLoadException(sprintf('The service file "%s" is not valid.', $file));
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
     * @param string $file
     * @return array
     *
     * @throws LoaderLoadException When service file is not valid
     */
    private function validate($content, string $file): array
    {
        if (null === $content) {
            return [];
        }

        if (!is_array($content)) {
            throw new LoaderLoadException(sprintf('The yaml file "%s" is not valid. It should contain an array. Check your YAML syntax.', $file));
        }

        if (isset($content['stages']) && !is_array($content['stages'])) {
            throw new LoaderLoadException(sprintf('The "stages" key should contain an array in %s. Check your YAML syntax.', $file));
        }

        return $content;
    }
}
<?php


namespace Nurschool\Shared\Infrastructure\Symfony\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SendGridExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $options = [];
        if ($config['host']) {
            $options['host'] = $config['host'];
        }
        if ($config['curl']) {
            $options['curl'] = $config['curl'];
        }

        $options = empty($options) ? null : $options;

        $container->getDefinition(\SendGrid::class)->setArguments([$config['api_key'], $options]);
    }
}
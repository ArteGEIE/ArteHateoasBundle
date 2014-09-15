<?php

namespace Arte\Bundle\HateoasBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ArteHateoasExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ('file' === $config['adder']['cache']) {
            $container
                ->getDefinition('arte.hateoas.adder.registry')
                ->replaceArgument(3, $config['adder']['always_generate']);
            $container
                ->getDefinition('arte.hateoas.adder.generator')
                ->replaceArgument(1, $config['adder']['file_cache']['dir'])
                ->replaceArgument(10, $config['adder']['always_generate']);

            $nsDir = $container->getParameterBag()->resolveValue($config['adder']['file_cache']['dir']);
            $addersDir = $nsDir.'/Arte/Bundle/HateoasBundle/Adder/Generated';

            if (!file_exists($addersDir)) {
                if (!$rs = @mkdir($addersDir, 0777, true)) {
                    throw new \RuntimeException(sprintf('Could not create hateaos adders cache directory "%s".', $addersDir));
                }
            }
        }
    }
}

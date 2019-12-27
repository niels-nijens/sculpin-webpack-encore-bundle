<?php

declare(strict_types=1);

/*
 * This file is part of the SculpinWebpackEncoreBundle package.
 *
 * (c) Niels Nijens <nijens.niels@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nijens\SculpinWebpackEncoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\WebpackEncoreBundle\DependencyInjection\WebpackEncoreExtension;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

/**
 * Loads the services and configuration into the service container.
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
final class NijensSculpinWebpackEncoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('nijens_sculpin_webpack_encore.assets.manifest_base_path', $config['output_path']);

        $manifestFile = $config['output_path'].'/manifest.json';
        if (isset($config['assets']['json_manifest_path'])) {
            $manifestFile = $config['assets']['json_manifest_path'];
        }
        $container->setParameter('nijens_sculpin_webpack_encore.assets.manifest', $manifestFile);

        $loader = new XmlFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $this->loadWebpackEncoreExtension($config, $container);
    }

    /**
     * Loads the extension from the {@see WebpackEncoreBundle}.
     */
    private function loadWebpackEncoreExtension(array $config, ContainerBuilder $container): void
    {
        $webpackExtension = new WebpackEncoreExtension();
        $webpackExtension->load([$config], $container);
    }
}

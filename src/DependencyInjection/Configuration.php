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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\WebpackEncoreBundle\DependencyInjection\Configuration as WebpackEncoreBundleConfiguration;

/**
 * Validates and merges configuration from the configuration files.
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('nijens_sculpin_webpack_encore');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('assets')
                    ->info('Assets configuration')
                    ->children()
                        ->scalarNode('json_manifest_path')
                            ->defaultNull()
                            ->end()
                    ->end()
                ->end()
            ->end();

        $this->appendWebpackEncoreConfiguration($treeBuilder);

        return $treeBuilder;
    }

    /**
     * Appends the 'output_path' and 'builds' configuration nodes to the root node.
     */
    private function appendWebpackEncoreConfiguration(TreeBuilder $treeBuilder): void
    {
        $rootNode = $treeBuilder->getRootNode();

        $webpackEncoreConfiguration = new WebpackEncoreBundleConfiguration();
        $webpackEncoreChildNodeDefinitions = $webpackEncoreConfiguration->getConfigTreeBuilder()
            ->getRootNode()
            ->getChildNodeDefinitions();

        foreach ($webpackEncoreChildNodeDefinitions as $webpackEncoreChildNodeName => $webpackEncoreChildNodeDefinition) {
            if (in_array($webpackEncoreChildNodeName, ['output_path', 'builds'])) {
                $rootNode->append($webpackEncoreChildNodeDefinition);
            }
        }
    }
}

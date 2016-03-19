<?php

namespace EmanueleMinotto\SafeBrowsingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * {@inheritdoc}
 */
class SafeBrowsingExtension extends Extension
{
    /**
     * Main service ID.
     *
     * @var string
     */
    const SERVICE_ID = 'safe_browsing_bundle.validator.constraints.safe_validator';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new Loader\XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $this->setCache($config['cache'], $container);
        $this->setConfigs($config['configs'], $container);
        $this->setHttpClient($config['http_client'], $container);
    }

    /**
     * Set cache directory.
     *
     * @param string           $cache
     * @param ContainerBuilder $container
     */
    private function setCache($cache, ContainerBuilder $container)
    {
        if (!$cache) {
            return;
        }

        $container
            ->getDefinition(self::SERVICE_ID)
            ->addMethodCall('setCache', [new Reference($cache)]);
    }

    /**
     * Initialize service configuration.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    private function setConfigs(array $configs, ContainerBuilder $container)
    {
        $container
            ->getDefinition(self::SERVICE_ID)
            ->replaceArgument(0, $configs);
    }

    /**
     * Set HTTP client.
     *
     * @param string           $httpClient
     * @param ContainerBuilder $container
     */
    private function setHttpClient($httpClient, ContainerBuilder $container)
    {
        if (!$httpClient) {
            return;
        }

        $container
            ->getDefinition(self::SERVICE_ID)
            ->addMethodCall('setHttpClient', [new Reference($httpClient)]);
    }
}

<?php

namespace EmanueleMinotto\SafeBrowsingBundle\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;

/**
 * @covers EmanueleMinotto\SafeBrowsingBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtension()
    {
        return new SafeBrowsingExtension();
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     * Test configuration formats.
     */
    public function testConfigurationFormats()
    {
        $this->assertProcessedConfigurationEquals(
            [
                'configs' => [
                    'key' => 'apikey',
                    'client' => 'app',
                    'appver' => '2.3.4',
                    'pver' => 3.1,
                ],
                'cache' => 'caching.service',
                'http_client' => 'http_client.service',
            ],
            [
                __DIR__.'/Fixtures/config.yml',
                __DIR__.'/Fixtures/config.xml',
            ]
        );
    }
}

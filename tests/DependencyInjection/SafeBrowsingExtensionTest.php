<?php

namespace EmanueleMinotto\SafeBrowsingBundle\DependencyInjection;

use EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints\SafeValidator;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * @covers EmanueleMinotto\SafeBrowsingBundle\DependencyInjection\SafeBrowsingExtension
 */
class SafeBrowsingExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new SafeBrowsingExtension(),
        ];
    }

    /**
     * Test parameter definition.
     */
    public function testParameter()
    {
        $this->load([
            'configs' => [
                'key' => sha1(mt_rand()),
            ],
        ]);

        $this->assertContainerBuilderHasParameter(
            'safe_browsing_bundle.validator.constraints.safe_validator.class',
            SafeValidator::class
        );
    }

    /**
     * Test service definition.
     *
     * @depends testParameter
     */
    public function testService()
    {
        $this->load([
            'configs' => [
                'key' => sha1(mt_rand()),
            ],
        ]);

        $this->assertContainerBuilderHasService(
            SafeBrowsingExtension::SERVICE_ID,
            SafeValidator::class
        );
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            SafeBrowsingExtension::SERVICE_ID,
            'validator.constraint_validator',
            [
                'alias' => 'safe',
            ]
        );
    }

    /**
     * Test service definition with full configuration.
     *
     * @depends testService
     */
    public function testServiceWithFullConfiguration()
    {
        $config = [
            'configs' => [
                'key' => sha1(mt_rand()),
                'client' => sha1(mt_rand()),
                'appver' => '2.3.4',
                'pver' => 3.1,
            ],
        ];
        $this->load($config);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            SafeBrowsingExtension::SERVICE_ID,
            0,
            $config['configs']
        );
    }

    /**
     * Test service definition with cache.
     *
     * @depends testService
     */
    public function testServiceWithCache()
    {
        $config = [
            'configs' => [
                'key' => sha1(mt_rand()),
            ],
            'cache' => sha1(mt_rand()),
        ];
        $this->load($config);

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            SafeBrowsingExtension::SERVICE_ID,
            'setCache',
            [
                $config['cache'],
            ]
        );
    }

    /**
     * Test service definition with HTTP client.
     *
     * @depends testService
     */
    public function testServiceWithHttpClient()
    {
        $config = [
            'configs' => [
                'key' => sha1(mt_rand()),
            ],
            'http_client' => sha1(mt_rand()),
        ];
        $this->load($config);

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            SafeBrowsingExtension::SERVICE_ID,
            'setHttpClient',
            [
                $config['http_client'],
            ]
        );
    }
}

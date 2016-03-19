<?php

namespace EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validator used for URL safety evaluation.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class SafeValidator extends ConstraintValidator
{
    /**
     * Google safe browsing endpoint.
     *
     * @var string
     */
    const ENDPOINT = 'https://sb-ssl.google.com/safebrowsing/api/lookup';

    /**
     * HTTP client.
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * Validator cache.
     *
     * @var Cache
     */
    private $cache;

    /**
     * Request configurations.
     *
     * @var array
     */
    private $configs = [];

    /**
     * Constructor with attributes initialization.
     */
    public function __construct(array $configs = [])
    {
        $this->cache = new ArrayCache();
        $this->configs = $configs;
        $this->httpClient = new Client();
    }

    /**
     * Sets the HTTP client.
     *
     * @param ClientInterface $httpClient The HTTP client.
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Sets the Validator cache.
     *
     * @param Cache $cache The caching implementation.
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        $response = $this->getResponse($value);
        if (false === $response || 'ok' === $response) {
            return;
        }

        $this->context->addViolation($constraint->message, [
            '%response%' => $response,
        ]);
    }

    /**
     * Gets service output and cache it.
     *
     * @param string $url URL or domain.
     *
     * @return string|false
     */
    private function getResponse($url)
    {
        if ($this->cache->contains($url)) {
            return $this->cache->fetch($url);
        }

        $serviceOutput = $this->getHttpClientOutput($url);
        $this->cache->save($url, $serviceOutput);

        return $serviceOutput;
    }

    /**
     * Gets service output.
     *
     * @param string $url URL or domain.
     *
     * @return string|false
     */
    private function getHttpClientOutput($url)
    {
        try {
            $response = $this->httpClient->request('GET', self::ENDPOINT, [
                'query' => array_merge($this->configs, ['url' => $url]),
            ]);

            return trim($response->getBody()) ?: false;
        } catch (GuzzleException $exception) {
            return false;
        }
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Builds HTTP-backed terminology clients for a given server base URL.
 *
 * Use this when terminology validation should hit a live FHIR terminology server. When a
 * PSR-6 cache pool is supplied, each created client is wrapped in a CachingFHIRTerminologyClient
 * so repeated lookups are served from cache.
 */
final class HttpFHIRTerminologyClientFactory implements FHIRTerminologyClientFactoryInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ?CacheItemPoolInterface $cache = null,
        private readonly int $ttl = 3600,
        private readonly bool $usePost = false,
    ) {
    }

    public function createForServer(string $baseUrl): FHIRTerminologyClientInterface
    {
        $client = new HttpFHIRTerminologyClient($this->httpClient, $baseUrl, $this->usePost);

        if ($this->cache !== null) {
            return new CachingFHIRTerminologyClient($client, $this->cache, $this->ttl);
        }

        return $client;
    }
}

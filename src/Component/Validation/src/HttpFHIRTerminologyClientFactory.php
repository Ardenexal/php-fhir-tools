<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpFHIRTerminologyClientFactory implements FHIRTerminologyClientFactoryInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ?CacheItemPoolInterface $cache = null,
        private readonly int $ttl = 3600,
    ) {
    }

    public function createForServer(string $baseUrl): FHIRTerminologyClientInterface
    {
        $client = new HttpFHIRTerminologyClient($this->httpClient, $baseUrl);

        if ($this->cache !== null) {
            return new CachingFHIRTerminologyClient($client, $this->cache, $this->ttl);
        }

        return $client;
    }
}

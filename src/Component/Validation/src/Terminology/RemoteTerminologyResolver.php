<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Terminology;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Remote terminology resolver using FHIR terminology server.
 *
 * Implements $validate-code and $expand operations with:
 * - PSR-6 caching (24-72h TTL)
 * - Circuit breaker for resilience
 * - Configurable timeouts
 * - LRU-bounded cache
 *
 * @author Alex Murray <alex@ardenexal.com>
 */
final class RemoteTerminologyResolver implements TerminologyResolverInterface
{
    private const CACHE_TTL_VALIDATE = 259200; // 72 hours
    private const CACHE_TTL_EXPAND = 259200; // 72 hours
    private const TIMEOUT_MS = 500;

    public function __construct(
        private readonly string $baseUrl,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly CacheItemPoolInterface $cache,
        private readonly CircuitBreaker $circuitBreaker,
        private readonly int $timeout = self::TIMEOUT_MS,
    ) {
    }

    public function validateCode(
        string $valueSetUrl,
        string $system,
        string $code,
        ?string $display = null,
        ?string $version = null
    ): bool {
        // Check cache first
        $cacheKey = $this->getValidateCodeCacheKey($valueSetUrl, $system, $code, $version);
        $item = $this->cache->getItem($cacheKey);
        
        if ($item->isHit()) {
            return $item->get();
        }

        // Check circuit breaker
        if (!$this->circuitBreaker->isAvailable('terminology_server')) {
            throw new \RuntimeException('Terminology server circuit breaker is OPEN');
        }

        try {
            $result = $this->doValidateCode($valueSetUrl, $system, $code, $display, $version);
            
            // Cache result
            $item->set($result);
            $item->expiresAfter(self::CACHE_TTL_VALIDATE);
            $this->cache->save($item);
            
            $this->circuitBreaker->recordSuccess('terminology_server');
            
            return $result;
        } catch (\Exception $e) {
            $this->circuitBreaker->recordFailure('terminology_server');
            throw $e;
        }
    }

    public function expand(
        string $valueSetUrl,
        ?string $version = null,
        ?int $count = null,
        ?int $offset = null
    ): array {
        // Check cache first
        $cacheKey = $this->getExpandCacheKey($valueSetUrl, $version);
        $item = $this->cache->getItem($cacheKey);
        
        if ($item->isHit()) {
            $allCodes = $item->get();
            
            // Apply pagination if requested
            if ($offset !== null || $count !== null) {
                $start = $offset ?? 0;
                $length = $count ?? count($allCodes);
                return array_slice($allCodes, $start, $length);
            }
            
            return $allCodes;
        }

        // Check circuit breaker
        if (!$this->circuitBreaker->isAvailable('terminology_server')) {
            throw new \RuntimeException('Terminology server circuit breaker is OPEN');
        }

        try {
            $result = $this->doExpand($valueSetUrl, $version, $count, $offset);
            
            // Cache full expansion (without pagination)
            if ($offset === null && $count === null) {
                $item->set($result);
                $item->expiresAfter(self::CACHE_TTL_EXPAND);
                $this->cache->save($item);
            }
            
            $this->circuitBreaker->recordSuccess('terminology_server');
            
            return $result;
        } catch (\Exception $e) {
            $this->circuitBreaker->recordFailure('terminology_server');
            throw $e;
        }
    }

    public function canResolve(string $valueSetUrl): bool
    {
        // Remote resolver can attempt any ValueSet
        return true;
    }

    /**
     * Perform $validate-code operation against terminology server.
     */
    private function doValidateCode(
        string $valueSetUrl,
        string $system,
        string $code,
        ?string $display,
        ?string $version
    ): bool {
        $params = [
            'url' => $valueSetUrl,
            'system' => $system,
            'code' => $code,
        ];
        
        if ($display !== null) {
            $params['display'] = $display;
        }
        
        if ($version !== null) {
            $params['valueSetVersion'] = $version;
        }

        $url = $this->baseUrl . '/ValueSet/$validate-code?' . http_build_query($params);
        
        $request = $this->requestFactory->createRequest('GET', $url);
        $request = $request->withHeader('Accept', 'application/fhir+json');
        $request = $request->withHeader('X-Timeout', (string) $this->timeout);
        
        $response = $this->httpClient->sendRequest($request);
        
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                'Terminology server returned status ' . $response->getStatusCode()
            );
        }
        
        $body = (string) $response->getBody();
        $parameters = json_decode($body, true);
        
        if (!isset($parameters['resourceType']) || $parameters['resourceType'] !== 'Parameters') {
            throw new \RuntimeException('Invalid response from terminology server');
        }
        
        // Find "result" parameter
        foreach ($parameters['parameter'] ?? [] as $param) {
            if ($param['name'] === 'result' && isset($param['valueBoolean'])) {
                return $param['valueBoolean'];
            }
        }
        
        return false;
    }

    /**
     * Perform $expand operation against terminology server.
     */
    private function doExpand(
        string $valueSetUrl,
        ?string $version,
        ?int $count,
        ?int $offset
    ): array {
        $params = ['url' => $valueSetUrl];
        
        if ($version !== null) {
            $params['valueSetVersion'] = $version;
        }
        
        if ($count !== null) {
            $params['count'] = $count;
        }
        
        if ($offset !== null) {
            $params['offset'] = $offset;
        }

        $url = $this->baseUrl . '/ValueSet/$expand?' . http_build_query($params);
        
        $request = $this->requestFactory->createRequest('GET', $url);
        $request = $request->withHeader('Accept', 'application/fhir+json');
        $request = $request->withHeader('X-Timeout', (string) $this->timeout);
        
        $response = $this->httpClient->sendRequest($request);
        
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                'Terminology server returned status ' . $response->getStatusCode()
            );
        }
        
        $body = (string) $response->getBody();
        $valueSet = json_decode($body, true);
        
        if (!isset($valueSet['resourceType']) || $valueSet['resourceType'] !== 'ValueSet') {
            throw new \RuntimeException('Invalid response from terminology server');
        }
        
        $codes = [];
        foreach ($valueSet['expansion']['contains'] ?? [] as $concept) {
            $code = [
                'system' => $concept['system'] ?? '',
                'code' => $concept['code'] ?? '',
            ];
            
            if (isset($concept['display'])) {
                $code['display'] = $concept['display'];
            }
            
            $codes[] = $code;
        }
        
        return $codes;
    }

    /**
     * Generate cache key for validateCode operation.
     */
    private function getValidateCodeCacheKey(
        string $valueSetUrl,
        string $system,
        string $code,
        ?string $version
    ): string {
        $key = 'validate_code_' . md5($valueSetUrl . '|' . $system . '|' . $code . '|' . ($version ?? ''));
        return str_replace(['\\', '/', '{', '}', '(', ')', '@', ':'], '_', $key);
    }

    /**
     * Generate cache key for expand operation.
     */
    private function getExpandCacheKey(string $valueSetUrl, ?string $version): string
    {
        $key = 'expand_' . md5($valueSetUrl . '|' . ($version ?? ''));
        return str_replace(['\\', '/', '{', '}', '(', ')', '@', ':'], '_', $key);
    }
}

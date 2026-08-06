<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\OAuth;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Ardenexal\FHIRTools\Component\HttpClient\CachingFHIRTerminologyClient;

/**
 * Fetches (and optionally caches) an OAuth 2.0 access token via the client credentials grant
 * (RFC 6749 §4.4): a single `getAccessToken(): string` call POSTs `grant_type=client_credentials` plus
 * the client id/secret (and optional scope) to the configured token endpoint and returns the resulting
 * bearer token.
 *
 * Every failure mode — transport error, non-2xx response, malformed body — raises
 * {@see OAuthTokenException} with a fixed, generic message. The client secret and the token endpoint's
 * raw response are never included in any exception message, so this can be safely surfaced in a
 * user-facing error (see {@see OAuthTokenException}'s own docblock).
 *
 * When a PSR-6 cache pool is supplied, the token is cached keyed by token-URL + client-id, with a TTL of
 * the server's own `expires_in` minus a 60s safety margin — mirrors
 * {@see CachingFHIRTerminologyClient}'s in-process-then-PSR-6
 * layering, simplified to a single cached value per provider instance (no per-call variation exists here
 * the way it does for terminology lookups). A margin-adjusted TTL at or below zero is never persisted to
 * the pool at all (see {@see self::getAccessToken()}) — only served from the in-process token for the
 * remainder of this instance's lifetime.
 */
final class OAuthClientCredentialsTokenProvider
{
    private const SAFETY_MARGIN_SECONDS = 60;

    private const DEFAULT_TTL_SECONDS = 3600;

    private ?string $inProcessToken = null;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $tokenUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly ?string $scope = null,
        private readonly ?CacheItemPoolInterface $cache = null,
    ) {
    }

    /**
     * Returns a valid access token, from cache when available and unexpired, otherwise freshly fetched.
     *
     * @throws OAuthTokenException on any failure to obtain a token
     */
    public function getAccessToken(): string
    {
        if ($this->inProcessToken !== null) {
            return $this->inProcessToken;
        }

        $cacheKey = $this->cacheKey();

        if ($this->cache !== null) {
            $item = $this->cache->getItem($cacheKey);
            if ($item->isHit()) {
                $cached = $item->get();
                if (\is_string($cached) && $cached !== '') {
                    $this->inProcessToken = $cached;

                    return $cached;
                }
            }
        }

        [$token, $expiresIn]  = $this->fetchToken();
        $this->inProcessToken = $token;

        $ttl = $expiresIn - self::SAFETY_MARGIN_SECONDS;

        // A non-positive margin-adjusted TTL means the token is already at (or past) the point the
        // safety margin exists to guard against — it must NOT be persisted to the PSR-6 pool at all.
        // Passing null to expiresAfter() means "never expires" (persist indefinitely), which would be
        // the opposite of correct here: a short-lived token would be served long after it actually
        // expired, causing sustained auth failures until the pool entry is manually evicted. The
        // in-process token above still serves the remainder of this request/instance's lifetime.
        if ($this->cache !== null && $ttl > 0) {
            $item = $this->cache->getItem($cacheKey);
            $item->set($token);
            $item->expiresAfter($ttl);
            $this->cache->save($item);
        }

        return $token;
    }

    private function cacheKey(): string
    {
        return 'oauth_client_credentials_' . md5($this->tokenUrl . '|' . $this->clientId);
    }

    /**
     * @return array{0: string, 1: int} [access token, expires_in seconds]
     *
     * @throws OAuthTokenException
     */
    private function fetchToken(): array
    {
        $body = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
        if ($this->scope !== null && $this->scope !== '') {
            $body['scope'] = $this->scope;
        }

        try {
            $response = $this->httpClient->request('POST', $this->tokenUrl, [
                'body'    => $body,
                'headers' => ['Accept' => 'application/json'],
            ]);
            $status  = $response->getStatusCode();
            $content = $response->getContent(false);
        } catch (HttpClientExceptionInterface) {
            // -- rationale: never forward the wrapped exception's own message — Symfony's transport
            // exceptions are not contractually guaranteed to omit the request body, and the request body
            // here contains the client secret.
            throw new OAuthTokenException('Failed to reach the OAuth token endpoint.');
        }

        if ($status < 200 || $status >= 300) {
            throw new OAuthTokenException(\sprintf('OAuth token endpoint returned HTTP %d.', $status));
        }

        /** @var mixed $data */
        $data = json_decode($content, true);
        if (!\is_array($data) || !isset($data['access_token']) || !\is_string($data['access_token']) || $data['access_token'] === '') {
            throw new OAuthTokenException('OAuth token endpoint response did not include an access_token.');
        }

        $expiresIn = isset($data['expires_in']) && \is_int($data['expires_in']) ? $data['expires_in'] : self::DEFAULT_TTL_SECONDS;

        return [$data['access_token'], $expiresIn];
    }
}

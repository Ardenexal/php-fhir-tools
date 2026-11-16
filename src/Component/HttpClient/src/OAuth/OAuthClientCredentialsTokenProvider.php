<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\OAuth;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Clock\ClockInterface;
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
 * A token is reused until its expiry: the server's own `expires_in` minus a 60s safety margin. After
 * that, the next `getAccessToken()` call fetches a new one, so a long-running worker (a Messenger
 * consumer, a daemon) keeps working past the first token's lifetime. A freshly fetched token is always
 * returned for the call that fetched it, even when `expires_in` is inside the margin, but such a token
 * is never reused.
 *
 * When a PSR-6 cache pool is supplied, the token and its expiry are also cached under a key derived from
 * every token-issuance input (see {@see self::cacheKey()}), with the same margin-adjusted TTL. This
 * mirrors {@see CachingFHIRTerminologyClient}'s in-process-then-PSR-6 layering, simplified to a single
 * cached value per provider instance (no per-call variation exists here the way it does for terminology
 * lookups). A token whose margin-adjusted TTL is at or below zero is never written to the pool.
 *
 * Pass a PSR-20 clock to control "now" (tests, or an application-wide clock); without one the system
 * time is used.
 */
final class OAuthClientCredentialsTokenProvider
{
    private const SAFETY_MARGIN_SECONDS = 60;

    private const DEFAULT_TTL_SECONDS = 3600;

    /** One year: longer than any real access token lives, and small enough to add to a timestamp safely. */
    private const MAX_TTL_SECONDS = 31_536_000;

    private ?string $inProcessToken = null;

    /** Unix timestamp from which {@see self::$inProcessToken} must no longer be used. */
    private int $inProcessExpiresAt = 0;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $tokenUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly ?string $scope = null,
        private readonly ?CacheItemPoolInterface $cache = null,
        private readonly ?ClockInterface $clock = null,
    ) {
    }

    /**
     * Returns an unexpired access token from the in-process or PSR-6 cache, otherwise a freshly fetched one.
     *
     * @throws OAuthTokenException on any failure to obtain a token
     */
    public function getAccessToken(): string
    {
        $now = $this->now();

        if ($this->inProcessToken !== null && $now < $this->inProcessExpiresAt) {
            return $this->inProcessToken;
        }

        $this->inProcessToken = null;
        $cacheKey             = $this->cacheKey();

        if ($this->cache !== null) {
            $item = $this->cache->getItem($cacheKey);
            if ($item->isHit()) {
                $cached = $item->get();
                // The pool expires the item itself, but a pool that ignores TTLs, or one whose clock is
                // ahead of or behind this provider's, can return it late, so the stored expiry is checked too.
                if (\is_array($cached)
                    && isset($cached['token'], $cached['expires_at'])
                    && \is_string($cached['token']) && $cached['token'] !== ''
                    && \is_int($cached['expires_at']) && $now < $cached['expires_at']
                ) {
                    $this->inProcessToken     = $cached['token'];
                    $this->inProcessExpiresAt = $cached['expires_at'];

                    return $cached['token'];
                }
            }
        }

        [$token, $expiresIn] = $this->fetchToken();
        $ttl                 = $expiresIn - self::SAFETY_MARGIN_SECONDS;

        $this->inProcessToken     = $token;
        $this->inProcessExpiresAt = $now + $ttl;

        // A non-positive TTL means the token is already inside the safety margin, so it is not written
        // to the pool at all. Never "fix" this by passing null to expiresAfter(): null means "never
        // expires", which would serve the token long after the server stopped accepting it.
        if ($this->cache !== null && $ttl > 0) {
            $item = $this->cache->getItem($cacheKey);
            $item->set(['token' => $token, 'expires_at' => $now + $ttl]);
            $item->expiresAfter($ttl);
            $this->cache->save($item);
        }

        return $token;
    }

    /**
     * Drops $token from the in-process and PSR-6 caches so the next {@see self::getAccessToken()} call
     * fetches a new one. Call this when a server rejects the token (HTTP 401) before its expiry, for
     * example after the authorisation server revoked it. A different token already in the cache, such
     * as one another worker fetched in the meantime, is left alone.
     */
    public function invalidate(string $token): void
    {
        if ($this->inProcessToken === $token) {
            $this->inProcessToken = null;
        }

        if ($this->cache === null) {
            return;
        }

        $cacheKey = $this->cacheKey();
        $item     = $this->cache->getItem($cacheKey);
        $cached   = $item->isHit() ? $item->get() : null;
        if (\is_array($cached) && ($cached['token'] ?? null) === $token) {
            $this->cache->deleteItem($cacheKey);
        }
    }

    private function now(): int
    {
        return $this->clock?->now()->getTimestamp() ?? time();
    }

    /**
     * A PSR-6 pool is usually shared across provider instances, so the key must cover every input that
     * changes which token the endpoint issues: token URL, client id, client secret and scope. Otherwise
     * two callers with the same client id but different secrets (or scopes) would read each other's
     * token. The secret is the HMAC key rather than part of the hashed message, so the cache key can't
     * be used to test guesses at the secret without also knowing it.
     */
    private function cacheKey(): string
    {
        $issuanceInputs = json_encode([$this->tokenUrl, $this->clientId, $this->scope ?? ''], \JSON_THROW_ON_ERROR);

        return 'oauth_client_credentials_' . hash_hmac('sha256', $issuanceInputs, $this->clientSecret);
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

        return [$data['access_token'], self::expiresIn($data['expires_in'] ?? null)];
    }

    /**
     * Reads `expires_in` as whole seconds. Some token endpoints send it as a numeric string (`"3599"`),
     * which is accepted too. A missing or unreadable value falls back to one hour, and a value above
     * {@see self::MAX_TTL_SECONDS} is capped so the expiry timestamp cannot overflow.
     */
    private static function expiresIn(mixed $value): int
    {
        if (\is_string($value) && preg_match('/^\d{1,10}$/', $value) === 1) {
            $value = (int) $value;
        }

        if (!\is_int($value)) {
            return self::DEFAULT_TTL_SECONDS;
        }

        return min($value, self::MAX_TTL_SECONDS);
    }
}

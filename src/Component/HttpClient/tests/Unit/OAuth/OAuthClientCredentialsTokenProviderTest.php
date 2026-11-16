<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit\OAuth;

use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthClientCredentialsTokenProvider;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthTokenException;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class OAuthClientCredentialsTokenProviderTest extends TestCase
{
    private const string TOKEN_URL     = 'https://idp.example.org/token';

    private const string CLIENT_ID     = 'demo-client';

    private const string CLIENT_SECRET = 'super-secret-value';

    /** An arbitrary fixed "now" for tests that pin the clock. */
    private const int NOW = 1_700_000_000;

    public function testFetchesTokenViaClientCredentialsGrantFormBody(): void
    {
        $capturedBody = null;
        $mockClient   = new MockHttpClient(function(string $method, string $url, array $options) use (&$capturedBody): MockResponse {
            $capturedBody = $options['body'] ?? null;

            self::assertSame('POST', $method);
            self::assertSame(self::TOKEN_URL, $url);

            return new MockResponse(json_encode(['access_token' => 'tok-123', 'expires_in' => 3600]) ?: '{}');
        });

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET);
        $token    = $provider->getAccessToken();

        self::assertSame('tok-123', $token);
        parse_str((string) $capturedBody, $parsed);
        self::assertSame('client_credentials', $parsed['grant_type']);
        self::assertSame(self::CLIENT_ID, $parsed['client_id']);
        self::assertSame(self::CLIENT_SECRET, $parsed['client_secret']);
        self::assertArrayNotHasKey('scope', $parsed);
    }

    public function testScopeIncludedWhenProvided(): void
    {
        $capturedBody = null;
        $mockClient   = new MockHttpClient(function(string $_method, string $_url, array $options) use (&$capturedBody): MockResponse {
            $capturedBody = $options['body'] ?? null;

            return new MockResponse(json_encode(['access_token' => 'tok', 'expires_in' => 3600]) ?: '{}');
        });

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, 'system/*.read');
        $provider->getAccessToken();

        parse_str((string) $capturedBody, $parsed);
        self::assertSame('system/*.read', $parsed['scope']);
    }

    public function testInProcessCacheAvoidsSecondFetchWithinSameInstance(): void
    {
        $callCount  = 0;
        $mockClient = new MockHttpClient(function() use (&$callCount): MockResponse {
            ++$callCount;

            return new MockResponse(json_encode(['access_token' => 'tok', 'expires_in' => 3600]) ?: '{}');
        });

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET);
        $provider->getAccessToken();
        $provider->getAccessToken();

        self::assertSame(1, $callCount);
    }

    public function testPsr6CacheHitSkipsHttpCallEntirely(): void
    {
        $mockClient = new MockHttpClient(function(): MockResponse {
            self::fail('HTTP client should not be called on a PSR-6 cache hit.');
        });

        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn(['token' => 'cached-token', 'expires_at' => self::NOW + 600]);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool, clock: self::clockAt(self::NOW));

        self::assertSame('cached-token', $provider->getAccessToken());
    }

    public function testPsr6CacheMissFetchesAndSavesWithSafetyMarginTtl(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'fresh-tok', 'expires_in' => 3600]) ?: '{}'));

        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);
        $item->expects(self::once())->method('set')->with(['token' => 'fresh-tok', 'expires_at' => self::NOW + 3600 - 60]);
        $item->expects(self::once())->method('expiresAfter')->with(3600 - 60);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool, clock: self::clockAt(self::NOW));

        self::assertSame('fresh-tok', $provider->getAccessToken());
    }

    /**
     * A margin-adjusted TTL at or below zero (a token whose `expires_in` is already inside, or below,
     * the 60s safety margin) must never be persisted to the PSR-6 pool at all — passing `null` to
     * `expiresAfter()` means "never expires", which would cache an already-near-expiry token forever
     * instead of not caching it. The fetched token still serves this one `getAccessToken()` call.
     */
    public function testExpiresInAtOrBelowSafetyMarginIsNeverPersistedToTheCachePool(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'tok', 'expires_in' => 30]) ?: '{}'));

        $item = $this->createMock(CacheItemInterface::class);
        $item->expects(self::never())->method('set');
        $item->expects(self::never())->method('expiresAfter');

        $pool = $this->createMock(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);
        $pool->expects(self::never())->method('save');

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool);

        self::assertSame('tok', $provider->getAccessToken());
    }

    public function testMissingExpiresInDefaultsToOneHour(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'tok']) ?: '{}'));

        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);
        $item->expects(self::once())->method('expiresAfter')->with(3600 - 60);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool);
        $provider->getAccessToken();
    }

    /** Some token endpoints send `expires_in` as a numeric string; it must still set the expiry. */
    public function testNumericStringExpiresInIsHonoured(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);
        $item->expects(self::once())->method('expiresAfter')->with(300 - 60);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $mockClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'tok', 'expires_in' => '300']) ?: '{}'));
        $provider   = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool, clock: self::clockAt(self::NOW));
        $provider->getAccessToken();
    }

    /** An absurd `expires_in` is capped at one year instead of overflowing the expiry timestamp. */
    public function testHugeExpiresInIsCappedAtOneYear(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);
        $item->expects(self::once())->method('expiresAfter')->with(31_536_000 - 60);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $mockClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'tok', 'expires_in' => \PHP_INT_MAX]) ?: '{}'));
        $provider   = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool, clock: self::clockAt(self::NOW));

        self::assertSame('tok', $provider->getAccessToken());
    }

    public function testHttpErrorStatusThrowsGenericMessage(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('unauthorized', ['http_code' => 401]));
        $provider   = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET);

        try {
            $provider->getAccessToken();
            self::fail('Expected OAuthTokenException.');
        } catch (OAuthTokenException $e) {
            self::assertSame('OAuth token endpoint returned HTTP 401.', $e->getMessage());
        }
    }

    public function testTransportFailureThrowsGenericMessage(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('', ['error' => 'Connection refused']));
        $provider   = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET);

        try {
            $provider->getAccessToken();
            self::fail('Expected OAuthTokenException.');
        } catch (OAuthTokenException $e) {
            self::assertSame('Failed to reach the OAuth token endpoint.', $e->getMessage());
        }
    }

    public function testMalformedJsonResponseThrowsGenericMessage(): void
    {
        $mockClient = new MockHttpClient(new MockResponse('not-json'));
        $provider   = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET);

        try {
            $provider->getAccessToken();
            self::fail('Expected OAuthTokenException.');
        } catch (OAuthTokenException $e) {
            self::assertSame('OAuth token endpoint response did not include an access_token.', $e->getMessage());
        }
    }

    public function testMissingAccessTokenFieldThrowsGenericMessage(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(json_encode(['foo' => 'bar']) ?: '{}'));
        $provider   = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET);

        try {
            $provider->getAccessToken();
            self::fail('Expected OAuthTokenException.');
        } catch (OAuthTokenException $e) {
            self::assertSame('OAuth token endpoint response did not include an access_token.', $e->getMessage());
        }
    }

    /**
     * The milestone's core security guardrail: no failure mode may ever put the client secret into an
     * exception message, since callers (e.g. the demo's SdcController) surface `getMessage()` verbatim in
     * a browser-visible error panel.
     */
    public function testClientSecretNeverAppearsInAnyExceptionMessage(): void
    {
        $failureModes = [
            'transport error' => new MockResponse('', ['error' => 'Connection refused']),
            'http error'      => new MockResponse('nope', ['http_code' => 500]),
            'malformed json'  => new MockResponse('not-json'),
            'missing token'   => new MockResponse(json_encode(['foo' => 'bar']) ?: '{}'),
        ];

        foreach ($failureModes as $label => $response) {
            $mockClient = new MockHttpClient($response);
            $provider   = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET);

            try {
                $provider->getAccessToken();
                self::fail(\sprintf('Expected OAuthTokenException for failure mode: %s', $label));
            } catch (OAuthTokenException $e) {
                self::assertStringNotContainsString(self::CLIENT_SECRET, $e->getMessage(), \sprintf('Secret leaked for failure mode: %s', $label));
            }
        }
    }

    /**
     * A shared PSR-6 pool must not hand one caller's token to another caller who shares only the token
     * URL and client id: the secret and scope change which token is issued, so they belong in the key.
     */
    public function testCacheKeyCoversClientSecretAndScope(): void
    {
        $base          = $this->cacheKeyFor(self::CLIENT_SECRET, null);
        $sameInputs    = $this->cacheKeyFor(self::CLIENT_SECRET, null);
        $otherSecret   = $this->cacheKeyFor('a-different-secret', null);
        $withScope     = $this->cacheKeyFor(self::CLIENT_SECRET, 'system/*.read');
        $otherScope    = $this->cacheKeyFor(self::CLIENT_SECRET, 'system/*.write');

        self::assertSame($base, $sameInputs);
        self::assertNotSame($base, $otherSecret);
        self::assertNotSame($base, $withScope);
        self::assertNotSame($withScope, $otherScope);
        self::assertStringNotContainsString(self::CLIENT_SECRET, $base);
    }

    /**
     * The long-running-worker case: a provider that lives longer than its token must fetch a new one
     * once `expires_in` minus the 60s safety margin has passed, not keep returning the first token.
     */
    public function testFetchesNewTokenOnceSafetyMarginIsReached(): void
    {
        $clock    = self::clockAt(self::NOW);
        $provider = new OAuthClientCredentialsTokenProvider($this->countingTokenEndpoint(3600), self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, clock: $clock);

        self::assertSame('tok-1', $provider->getAccessToken());

        $clock->now = self::NOW + 3600 - 61;
        self::assertSame('tok-1', $provider->getAccessToken(), 'Token must be reused while outside the safety margin.');

        $clock->now = self::NOW + 3600 - 60;
        self::assertSame('tok-2', $provider->getAccessToken(), 'Token must be replaced once the safety margin is reached.');
        self::assertSame('tok-2', $provider->getAccessToken());
    }

    /** A token whose `expires_in` is already inside the margin serves the call that fetched it, and no other. */
    public function testTokenInsideSafetyMarginIsNotReused(): void
    {
        $provider = new OAuthClientCredentialsTokenProvider($this->countingTokenEndpoint(30), self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, clock: self::clockAt(self::NOW));

        self::assertSame('tok-1', $provider->getAccessToken());
        self::assertSame('tok-2', $provider->getAccessToken());
    }

    /** A pool entry past its stored expiry (a pool that returns items late) is treated as a miss. */
    public function testExpiredPsr6EntryIsIgnored(): void
    {
        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn(['token' => 'stale-token', 'expires_at' => self::NOW]);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $provider = new OAuthClientCredentialsTokenProvider($this->countingTokenEndpoint(3600), self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool, clock: self::clockAt(self::NOW));

        self::assertSame('tok-1', $provider->getAccessToken());
    }

    public function testInvalidateForcesNewFetchAndDeletesMatchingPoolEntry(): void
    {
        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn(['token' => 'tok-1', 'expires_at' => self::NOW + 600]);

        $pool = $this->createMock(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);
        $pool->expects(self::once())->method('deleteItem');

        $provider = new OAuthClientCredentialsTokenProvider($this->countingTokenEndpoint(3600), self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, clock: self::clockAt(self::NOW));
        $provider->getAccessToken();
        $provider->invalidate('tok-1');
        self::assertSame('tok-2', $provider->getAccessToken());

        $pooled = new OAuthClientCredentialsTokenProvider($this->countingTokenEndpoint(3600), self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool, clock: self::clockAt(self::NOW));
        $pooled->invalidate('tok-1');
    }

    /** Another worker may already have replaced the pooled token; invalidating an older one must not delete it. */
    public function testInvalidateLeavesADifferentPooledTokenAlone(): void
    {
        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn(['token' => 'newer-token', 'expires_at' => self::NOW + 600]);

        $pool = $this->createMock(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);
        $pool->expects(self::never())->method('deleteItem');

        $provider = new OAuthClientCredentialsTokenProvider($this->countingTokenEndpoint(3600), self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool, clock: self::clockAt(self::NOW));
        $provider->invalidate('older-token');
    }

    /** A token endpoint that issues `tok-1`, `tok-2`, ... with the given `expires_in`. */
    private function countingTokenEndpoint(int $expiresIn): MockHttpClient
    {
        $issued = 0;

        return new MockHttpClient(static function() use (&$issued, $expiresIn): MockResponse {
            ++$issued;

            return new MockResponse(json_encode(['access_token' => 'tok-' . $issued, 'expires_in' => $expiresIn]) ?: '{}');
        });
    }

    /** A clock whose current Unix time is the public, writable `$now`. */
    private static function clockAt(int $now): MutableClock
    {
        return new MutableClock($now);
    }

    /** Returns the PSR-6 key a provider with the given secret and scope reads its token from. */
    private function cacheKeyFor(string $clientSecret, ?string $scope): string
    {
        $requestedKeys = [];

        $item = $this->createStub(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn(['token' => 'cached-token', 'expires_at' => \PHP_INT_MAX]);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturnCallback(static function(string $key) use (&$requestedKeys, $item): CacheItemInterface {
            $requestedKeys[] = $key;

            return $item;
        });

        $mockClient = new MockHttpClient(static function(): MockResponse {
            self::fail('HTTP client should not be called on a PSR-6 cache hit.');
        });

        (new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, $clientSecret, $scope, $pool))->getAccessToken();

        self::assertCount(1, $requestedKeys);

        return $requestedKeys[0];
    }
}

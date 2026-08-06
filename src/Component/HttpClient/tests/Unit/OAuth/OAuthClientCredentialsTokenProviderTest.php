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
        $item->method('get')->willReturn('cached-token');

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool);

        self::assertSame('cached-token', $provider->getAccessToken());
    }

    public function testPsr6CacheMissFetchesAndSavesWithSafetyMarginTtl(): void
    {
        $mockClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'fresh-tok', 'expires_in' => 3600]) ?: '{}'));

        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);
        $item->expects(self::once())->method('set')->with('fresh-tok');
        $item->expects(self::once())->method('expiresAfter')->with(3600 - 60);

        $pool = $this->createStub(CacheItemPoolInterface::class);
        $pool->method('getItem')->willReturn($item);

        $provider = new OAuthClientCredentialsTokenProvider($mockClient, self::TOKEN_URL, self::CLIENT_ID, self::CLIENT_SECRET, cache: $pool);

        self::assertSame('fresh-tok', $provider->getAccessToken());
    }

    /**
     * A margin-adjusted TTL at or below zero (a token whose `expires_in` is already inside, or below,
     * the 60s safety margin) must never be persisted to the PSR-6 pool at all — passing `null` to
     * `expiresAfter()` means "never expires", which would cache an already-near-expiry token forever
     * instead of not caching it. The in-process token still serves this one `getAccessToken()` call (and
     * any further calls on this same provider instance); only the pool write is skipped.
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
}

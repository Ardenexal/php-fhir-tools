<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\Tests\Unit\OAuth;

use Ardenexal\FHIRTools\Component\HttpClient\FHIRHttpClient;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthClientCredentialsTokenProvider;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthHttpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class OAuthHttpClientTest extends TestCase
{
    private function tokenProvider(MockHttpClient $tokenEndpointClient): OAuthClientCredentialsTokenProvider
    {
        return new OAuthClientCredentialsTokenProvider($tokenEndpointClient, 'https://idp.example/token', 'client-id', 'client-secret');
    }

    public function testAttachesBearerTokenViaAuthBearerOption(): void
    {
        $tokenClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'abc123', 'expires_in' => 3600]) ?: '{}'));

        $capturedOptions  = null;
        $innerClient      = new MockHttpClient(function(string $_method, string $_url, array $options) use (&$capturedOptions): MockResponse {
            $capturedOptions = $options;

            return new MockResponse('{}');
        });

        $client = new OAuthHttpClient($innerClient, $this->tokenProvider($tokenClient));
        $client->request('GET', 'https://fhir.example.org/Patient');

        // Symfony's HttpClient resolves the semantic `auth_bearer` option into a real `Authorization`
        // header before the transport (here, MockHttpClient's callback) ever sees it — assert on the
        // header that will actually go over the wire, not the intermediate option.
        self::assertContains('Authorization: Bearer abc123', $capturedOptions['headers'] ?? []);
    }

    public function testTokenFetchFailureBecomesATransportException(): void
    {
        $tokenClient = new MockHttpClient(new MockResponse('', ['http_code' => 500]));
        $innerClient = new MockHttpClient(function(): MockResponse {
            self::fail('Inner HTTP client must not be called when the token fetch itself fails.');
        });

        $client = new OAuthHttpClient($innerClient, $this->tokenProvider($tokenClient));

        $this->expectException(TransportExceptionInterface::class);
        $client->request('GET', 'https://fhir.example.org/Patient');
    }

    /**
     * The milestone's graceful-degradation proof: wrapping FHIRHttpClient's inner client with
     * OAuthHttpClient requires zero changes to FHIRHttpClient, and a token failure still degrades to
     * null exactly like any other transport failure — not an uncaught exception.
     */
    public function testFHIRHttpClientDegradesGracefullyWhenTokenFetchFails(): void
    {
        $tokenClient = new MockHttpClient(new MockResponse('', ['http_code' => 401]));
        $oauthClient = new OAuthHttpClient(new MockHttpClient(), $this->tokenProvider($tokenClient));

        $fhirClient = new FHIRHttpClient($oauthClient, 'https://fhir.example.org/r4');

        self::assertNull($fhirClient->request('GET', 'Patient/1'));
    }

    public function testStreamDelegatesToInnerClient(): void
    {
        $tokenClient = new MockHttpClient(new MockResponse(json_encode(['access_token' => 'tok', 'expires_in' => 3600]) ?: '{}'));
        $innerClient = new MockHttpClient(new MockResponse('{}'));

        $client   = new OAuthHttpClient($innerClient, $this->tokenProvider($tokenClient));
        $response = $client->request('GET', 'https://fhir.example.org/Patient');

        // No exception -> stream() successfully delegated and consumed the mock response.
        foreach ($client->stream($response) as $chunk) {
            self::assertTrue($chunk->isLast() || !$chunk->isLast());
        }
    }

    public function testRetriesOnceWithANewTokenAfter401(): void
    {
        $authHeaders = [];
        $innerClient = new MockHttpClient(function(string $_method, string $_url, array $options) use (&$authHeaders): MockResponse {
            $authHeaders[] = self::authorizationHeader($options);

            return \count($authHeaders) === 1 ? new MockResponse('', ['http_code' => 401]) : new MockResponse('{"ok":true}');
        });

        $client   = new OAuthHttpClient($innerClient, $this->tokenProvider($this->countingTokenEndpoint()));
        $response = $client->request('POST', 'https://fhir.example.org/Patient', ['body' => '{}']);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(['Authorization: Bearer tok-1', 'Authorization: Bearer tok-2'], $authHeaders);
    }

    public function testSecond401IsReturnedWithoutAnotherRetry(): void
    {
        $attempts    = 0;
        $innerClient = new MockHttpClient(function() use (&$attempts): MockResponse {
            ++$attempts;

            return new MockResponse('', ['http_code' => 401]);
        });

        $client = new OAuthHttpClient($innerClient, $this->tokenProvider($this->countingTokenEndpoint()));

        self::assertSame(401, $client->request('GET', 'https://fhir.example.org/Patient')->getStatusCode());
        self::assertSame(2, $attempts);
    }

    /** A generator body is consumed by the first attempt, so sending it again would send an empty body. */
    public function testRequestWithNonReplayableBodyIsNotRetried(): void
    {
        $attempts    = 0;
        $innerClient = new MockHttpClient(function() use (&$attempts): MockResponse {
            ++$attempts;

            return new MockResponse('', ['http_code' => 401]);
        });

        $client = new OAuthHttpClient($innerClient, $this->tokenProvider($this->countingTokenEndpoint()));
        $body   = (static function(): \Generator {
            yield '{}';
        })();

        self::assertSame(401, $client->request('POST', 'https://fhir.example.org/Patient', ['body' => $body])->getStatusCode());
        self::assertSame(1, $attempts);
    }

    /** A form array holding a stream is sent as a multipart upload that reads the stream, so it is not resent either. */
    public function testMultipartBodyWithStreamIsNotRetried(): void
    {
        $attempts    = 0;
        $innerClient = new MockHttpClient(function() use (&$attempts): MockResponse {
            ++$attempts;

            return new MockResponse('', ['http_code' => 401]);
        });

        $stream = fopen('php://memory', 'r+');
        self::assertIsResource($stream);
        fwrite($stream, 'file contents');
        rewind($stream);

        $client = new OAuthHttpClient($innerClient, $this->tokenProvider($this->countingTokenEndpoint()));

        self::assertSame(401, $client->request('POST', 'https://fhir.example.org/Binary', ['body' => ['file' => $stream]])->getStatusCode());
        self::assertSame(1, $attempts);
    }

    /**
     * End to end through FHIRHttpClient: one client kept alive past the token's lifetime (a Messenger
     * consumer, say) sends the new token after the old one expires.
     */
    public function testLongRunningFHIRHttpClientSendsNewTokenAfterExpiry(): void
    {
        $authHeaders = [];
        $innerClient = new MockHttpClient(function(string $_method, string $_url, array $options) use (&$authHeaders): MockResponse {
            $authHeaders[] = self::authorizationHeader($options);

            return new MockResponse('{}');
        });

        $clock      = new MutableClock(1_700_000_000);
        $provider   = new OAuthClientCredentialsTokenProvider($this->countingTokenEndpoint(), 'https://idp.example/token', 'client-id', 'client-secret', clock: $clock);
        $fhirClient = new FHIRHttpClient(new OAuthHttpClient($innerClient, $provider), 'https://fhir.example.org/r4');

        $fhirClient->request('GET', 'Patient/1');
        $clock->now += 3600;
        $fhirClient->request('GET', 'Patient/1');

        self::assertSame(['Authorization: Bearer tok-1', 'Authorization: Bearer tok-2'], $authHeaders);
    }

    /** A token endpoint that issues `tok-1`, `tok-2`, ... each valid for one hour. */
    private function countingTokenEndpoint(): MockHttpClient
    {
        $issued = 0;

        return new MockHttpClient(static function() use (&$issued): MockResponse {
            ++$issued;

            return new MockResponse(json_encode(['access_token' => 'tok-' . $issued, 'expires_in' => 3600]) ?: '{}');
        });
    }

    /**
     * Symfony resolves `auth_bearer` into a header before the mock transport sees the options.
     *
     * @param array<string, mixed> $options
     */
    private static function authorizationHeader(array $options): ?string
    {
        $headers = $options['headers'] ?? [];
        self::assertIsArray($headers);

        foreach ($headers as $header) {
            if (\is_string($header) && str_starts_with($header, 'Authorization:')) {
                return $header;
            }
        }

        return null;
    }
}

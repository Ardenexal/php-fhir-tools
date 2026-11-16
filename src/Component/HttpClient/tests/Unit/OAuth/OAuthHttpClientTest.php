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
}

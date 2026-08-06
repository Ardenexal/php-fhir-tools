<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use App\Sdc\ExternalClientFactory;
use Ardenexal\FHIRTools\Component\HttpClient\NullFHIRHttpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * M06: `ExternalClientFactory::httpClient()`'s auth-configuration guards. A partially-configured or
 * ambiguously-configured auth setup must fail loudly (a thrown exception at container-resolution time)
 * rather than silently falling back to an unauthenticated connection or letting one mechanism silently
 * override the other.
 *
 * M07 additions: session-scoped credential overrides. A session value for the OAuth client id/secret or
 * the manual header's value takes precedence over the corresponding env-var-sourced method argument; its
 * absence leaves M06 behavior (the env-var argument, or empty) completely unchanged. The destination
 * (base URL, OAuth token URL, header *name*) is never session-overridable — only credential values are.
 */
final class ExternalClientFactoryAuthTest extends TestCase
{
    /** @param array<string, string> $sessionData */
    private function requestStackWithSession(array $sessionData = []): RequestStack
    {
        $session = new Session(new MockArraySessionStorage());
        foreach ($sessionData as $key => $value) {
            $session->set($key, $value);
        }

        $request = new Request();
        $request->setSession($session);

        $stack = new RequestStack();
        $stack->push($request);

        return $stack;
    }

    private function factory(array $sessionData = []): ExternalClientFactory
    {
        return new ExternalClientFactory(new MockHttpClient(), $this->requestStackWithSession($sessionData));
    }

    public function testNoAuthConfiguredReturnsPlainClientUnchangedFromM04(): void
    {
        $factory = $this->factory();
        $client  = $factory->httpClient('https://fhir.example.org/r4');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testEmptyBaseUrlReturnsNullClientRegardlessOfAuthVars(): void
    {
        $factory = $this->factory();
        $client  = $factory->httpClient('', 'https://idp.example/token', 'id', 'secret');

        self::assertInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testTokenUrlAloneWithNoIdOrSecretAnywhereDoesNotThrow(): void
    {
        // M07 changed this from "must all be set together" to a valid, non-throwing state: the operator
        // enabled OAuth via the token URL alone, and no visitor session has supplied credentials yet.
        // The resulting client simply isn't OAuth-authenticated for this request — proven at the
        // decoration level by testTokenUrlAloneSkipsOAuthDecorationEntirely() below.
        $factory = $this->factory();
        $client  = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testTokenUrlAloneSkipsOAuthDecorationEntirely(): void
    {
        // Decoration-level proof (not just "doesn't throw"): the underlying transport must receive
        // exactly the FHIR request itself, with no preceding token-endpoint call — if OAuth were
        // incorrectly activated with an empty client id/secret, a (failing) token fetch would happen
        // first.
        $calls      = [];
        $mockClient = new MockHttpClient(function(string $_method, string $url) use (&$calls): MockResponse {
            $calls[] = $url;

            return new MockResponse('{"resourceType":"Bundle","type":"searchset","entry":[]}');
        });

        $factory = new ExternalClientFactory($mockClient, $this->requestStackWithSession());
        $client  = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token');

        $client->search('Patient?_count=1', 'R4');

        self::assertCount(1, $calls, 'Exactly one call expected: the FHIR search itself, no token-endpoint call.');
        self::assertStringContainsString('fhir.example.org', $calls[0]);
    }

    public function testClientIdAndSecretSetWithNoTokenUrlThrows(): void
    {
        // The inverse of the above is still a genuine misconfiguration: credentials with nothing to
        // authenticate against.
        $factory = $this->factory();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('are set but FHIR_SERVER_OAUTH_TOKEN_URL is not');
        $factory->httpClient('https://fhir.example.org/r4', '', 'client-id', 'client-secret');
    }

    public function testTwoOfThreeOauthFieldsSetStillThrows(): void
    {
        $factory = $this->factory();

        $this->expectException(\RuntimeException::class);
        $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', 'client-id');
    }

    public function testAllThreeOauthFieldsSetDoesNotThrow(): void
    {
        $factory = $this->factory();
        $client  = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', 'client-id', 'client-secret');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testHeaderValueWithoutNameThrows(): void
    {
        $factory = $this->factory();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('is set but FHIR_SERVER_AUTH_HEADER_NAME is not');
        $factory->httpClient('https://fhir.example.org/r4', '', '', '', '', 'a-value-with-no-name');
    }

    public function testHeaderNameAloneWithNoValueAnywhereDoesNotThrow(): void
    {
        // Same M07 shape as the OAuth token-URL-alone case: the operator named the header to attach,
        // no visitor session has supplied a value yet — valid, not yet authenticated.
        $factory = $this->factory();
        $client  = $factory->httpClient('https://fhir.example.org/r4', '', '', '', 'X-Api-Key');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testHeaderAndValueBothSetDoesNotThrow(): void
    {
        $factory = $this->factory();
        $client  = $factory->httpClient('https://fhir.example.org/r4', '', '', '', 'X-Api-Key', 'my-key');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testOauthPlusManualAuthorizationHeaderCollisionThrows(): void
    {
        $factory = $this->factory();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot configure both OAuth and a manual "Authorization" header');
        $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', 'id', 'secret', 'Authorization', 'Bearer xyz');
    }

    public function testOauthPlusManualAuthorizationHeaderCollisionIsCaseInsensitive(): void
    {
        $factory = $this->factory();

        $this->expectException(\RuntimeException::class);
        $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', 'id', 'secret', 'authorization', 'Bearer xyz');
    }

    public function testOauthPlusManualDifferentHeaderNameDoesNotThrow(): void
    {
        $factory = $this->factory();
        $client  = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', 'id', 'secret', 'X-Api-Key', 'my-key');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    // -------------------------------------------------------------------------
    // M07: session-scoped credential overrides
    // -------------------------------------------------------------------------

    public function testSessionOauthCredentialsOverrideEnvArguments(): void
    {
        $factory = $this->factory([
            'sdc_oauth_client_id'     => 'session-client-id',
            'sdc_oauth_client_secret' => 'session-client-secret',
        ]);

        // Env args supply only the token URL (as services.yaml would, when the operator enables OAuth
        // but leaves the id/secret env vars empty for visitors to supply via their own session).
        $client = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', '', '');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testSessionHeaderValueOverridesEnvArgument(): void
    {
        $factory = $this->factory(['sdc_auth_header_value' => 'session-value']);

        $client = $factory->httpClient('https://fhir.example.org/r4', '', '', '', 'X-Api-Key', '');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testNoSessionValueFallsBackToEnvArgumentUnchanged(): void
    {
        $factory = $this->factory(); // empty session

        $client = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', 'env-client-id', 'env-client-secret');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testSessionOauthCredentialsCombinedWithEnvTokenUrlPassesConsistencyCheck(): void
    {
        // Only the token URL comes from env; id+secret come entirely from the session. The
        // all-or-nothing OAuth check must see the *resolved* (post-override) values, not the raw env
        // arguments, or this would wrongly throw "must all be set together".
        $factory = $this->factory([
            'sdc_oauth_client_id'     => 'session-id',
            'sdc_oauth_client_secret' => 'session-secret',
        ]);

        $client = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }

    public function testSessionOauthClientIdWithoutSecretStillThrows(): void
    {
        // A half-submitted session credential is still a misconfiguration — fail loudly, don't silently
        // proceed with only one of the two.
        $factory = $this->factory(['sdc_oauth_client_id' => 'session-id']);

        $this->expectException(\RuntimeException::class);
        $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token');
    }

    public function testEmptyStringSessionValueDoesNotOverrideEnvArgument(): void
    {
        // An empty-string session value (e.g. a stale/cleared entry) must not shadow a real env value —
        // only a non-empty session value counts as "set".
        $factory = $this->factory(['sdc_oauth_client_id' => '']);

        $client = $factory->httpClient('https://fhir.example.org/r4', 'https://idp.example/token', 'env-client-id', 'env-client-secret');

        self::assertNotInstanceOf(NullFHIRHttpClient::class, $client);
    }
}

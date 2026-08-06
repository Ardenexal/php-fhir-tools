<?php

declare(strict_types=1);

namespace App\Sdc;

use Ardenexal\FHIRTools\Component\HttpClient\FHIRHttpClient;
use Ardenexal\FHIRTools\Component\HttpClient\HttpFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\HttpClient\NullFHIRHttpClient;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthClientCredentialsTokenProvider;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthHttpClient;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\StaticHeaderHttpClient;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Resolves the live-vs-null FHIR/terminology client for an operator-configured base URL. A plain Symfony
 * alias can't switch classes at runtime (only at container-compile time), so `services.yaml` wires
 * `FHIRHttpClientInterface`/`FHIRTerminologyClientInterface` as factory services pointing at this class
 * instead — the empty-string branch runs on every container build, keyed off the env-var-sourced argument.
 *
 * `httpClient()` additionally supports two independent, composable authentication mechanisms for the
 * FHIR server connection: OAuth 2.0 client credentials ({@see OAuthClientCredentialsTokenProvider} /
 * {@see OAuthHttpClient}) and a manually-specified header ({@see StaticHeaderHttpClient}, e.g. a
 * hand-obtained bearer token or an API key). Both are decorators around the plain Symfony
 * `HttpClientInterface` — `FHIRHttpClient` itself needs no changes to benefit.
 *
 * The **destination** (`$baseUrl`, `$oauthTokenUrl`, and the header's *name*) stays operator-configured
 * only (M04/M06) — those five method parameters are always the env-var-sourced values `services.yaml`
 * passes in, never session data. The **credential values** (OAuth client id/secret, the header's value)
 * additionally check the current visitor's session first (M07): a session value overrides the
 * env-var-sourced argument for that one request; its absence leaves M06 behavior completely unchanged.
 * This is a deliberate, user-approved scope change — see `M07-session-scoped-credential-entry.md` — that
 * lets a visitor to `/sdc` test as a different "user" against the server the operator already pointed it
 * at, without being able to redirect it to a different server or IdP.
 */
final class ExternalClientFactory
{
    /** Public so `SdcController` can check "is a session override set" for the status badge/UI, without duplicating the key strings. */
    public const string SESSION_KEY_OAUTH_CLIENT_ID = 'sdc_oauth_client_id';

    public const string SESSION_KEY_OAUTH_CLIENT_SECRET = 'sdc_oauth_client_secret';

    public const string SESSION_KEY_AUTH_HEADER_VALUE = 'sdc_auth_header_value';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * @throws \RuntimeException when the auth configuration (after session overrides are applied) is
     *                           inconsistent (see {@see self::assertAuthConfigurationIsConsistent()}) —
     *                           deliberately not caught anywhere in the demo, so a misconfiguration
     *                           fails loudly at container-resolution time rather than silently falling
     *                           back to an unauthenticated or ambiguously-authenticated connection
     */
    public function httpClient(
        string $baseUrl,
        string $oauthTokenUrl = '',
        string $oauthClientId = '',
        string $oauthClientSecret = '',
        string $authHeaderName = '',
        string $authHeaderValue = '',
    ): FHIRHttpClientInterface {
        if ($baseUrl === '') {
            return new NullFHIRHttpClient();
        }

        $oauthClientId     = $this->sessionOverride(self::SESSION_KEY_OAUTH_CLIENT_ID)     ?? $oauthClientId;
        $oauthClientSecret = $this->sessionOverride(self::SESSION_KEY_OAUTH_CLIENT_SECRET) ?? $oauthClientSecret;
        $authHeaderValue   = $this->sessionOverride(self::SESSION_KEY_AUTH_HEADER_VALUE)   ?? $authHeaderValue;

        $this->assertAuthConfigurationIsConsistent($oauthTokenUrl, $oauthClientId, $oauthClientSecret, $authHeaderName, $authHeaderValue);

        $transport = $this->authenticatedTransport($oauthTokenUrl, $oauthClientId, $oauthClientSecret, $authHeaderName, $authHeaderValue);

        return new FHIRHttpClient($transport, $baseUrl);
    }

    /**
     * Reads one session-scoped credential value, or null when unset — the current visitor's own
     * `RequestStack`-resolved session, never any other visitor's (Symfony's session mechanism isolates
     * per-visitor via the session cookie; see `SdcSessionCredentialIsolationTest` for the proof).
     *
     * Returns null (no override — identical to "not set") when there is no active HTTP request at all
     * (e.g. a `KernelTestCase`-only container resolution with no request ever pushed onto the
     * `RequestStack`) — there is no "visitor" to have session-scoped credentials for in that case, so
     * falling through to the env-var argument is the correct behavior, not a bug being papered over.
     */
    private function sessionOverride(string $key): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null || !$request->hasSession()) {
            return null;
        }

        $value = $request->getSession()->get($key);

        return \is_string($value) && $value !== '' ? $value : null;
    }

    public function terminologyClient(string $baseUrl): FHIRTerminologyClientInterface
    {
        if ($baseUrl === '') {
            return new NullFHIRTerminologyClient();
        }

        return new HttpFHIRTerminologyClient(new FHIRHttpClient($this->httpClient, $baseUrl));
    }

    /**
     * Wraps the base Symfony `HttpClientInterface` with whichever auth decorators are configured. The
     * two mechanisms are independent and composable (different option keys — `auth_bearer` vs `headers`
     * — so nesting order doesn't matter) as long as {@see self::assertAuthConfigurationIsConsistent()}
     * has already ruled out the one genuine conflict (both targeting `Authorization`).
     */
    private function authenticatedTransport(
        string $oauthTokenUrl,
        string $oauthClientId,
        string $oauthClientSecret,
        string $authHeaderName,
        string $authHeaderValue,
    ): HttpClientInterface {
        $transport = $this->httpClient;

        if ($authHeaderName !== '') {
            $transport = new StaticHeaderHttpClient($transport, $authHeaderName, $authHeaderValue);
        }

        // Only wrap with OAuth once a client id *and* secret are actually present (from env or session) —
        // a token URL alone (M07: operator enabled OAuth, no visitor has supplied session credentials
        // yet) is a valid, non-throwing state, but must not attempt a token fetch with an empty secret.
        if ($oauthTokenUrl !== '' && $oauthClientId !== '' && $oauthClientSecret !== '') {
            $tokenProvider = new OAuthClientCredentialsTokenProvider($this->httpClient, $oauthTokenUrl, $oauthClientId, $oauthClientSecret);
            $transport     = new OAuthHttpClient($transport, $tokenProvider);
        }

        return $transport;
    }

    /**
     * A partially- or ambiguously-configured auth setup is a worse failure mode than an unauthenticated
     * connection: silently falling back, or silently letting one mechanism clobber the other, would be
     * confusing and hard to notice — all four cases below fail loudly instead.
     *
     * M07 nuance: a client id/secret pair (or a header value) is expected to arrive entirely via a
     * visitor's session rather than env vars, so "the destination is configured but no credential value
     * is present yet" is now a *valid* state (nothing throws; {@see self::authenticatedTransport()}
     * simply doesn't attach that mechanism for this request) — only a genuinely inconsistent shape
     * (a credential present with nothing to attach it to, or a mismatched id/secret pair) still throws.
     */
    private function assertAuthConfigurationIsConsistent(
        string $oauthTokenUrl,
        string $oauthClientId,
        string $oauthClientSecret,
        string $authHeaderName,
        string $authHeaderValue,
    ): void {
        $hasClientId     = $oauthClientId     !== '';
        $hasClientSecret = $oauthClientSecret !== '';

        if ($oauthTokenUrl === '' && ($hasClientId || $hasClientSecret)) {
            throw new \RuntimeException('FHIR_SERVER_OAUTH_CLIENT_ID/FHIR_SERVER_OAUTH_CLIENT_SECRET (env or session) are set but FHIR_SERVER_OAUTH_TOKEN_URL is not — there is nothing to authenticate against.');
        }

        if ($hasClientId !== $hasClientSecret) {
            throw new \RuntimeException('FHIR_SERVER_OAUTH_CLIENT_ID and FHIR_SERVER_OAUTH_CLIENT_SECRET (env or session) must both be set together, or both left empty.');
        }

        if ($authHeaderName === '' && $authHeaderValue !== '') {
            throw new \RuntimeException('FHIR_SERVER_AUTH_HEADER_VALUE (env or session) is set but FHIR_SERVER_AUTH_HEADER_NAME is not — there is no header to attach it to.');
        }

        $oauthActive = $oauthTokenUrl !== '' && $hasClientId && $hasClientSecret;
        if ($oauthActive && $authHeaderName !== '' && strcasecmp($authHeaderName, 'Authorization') === 0) {
            throw new \RuntimeException('Cannot configure both OAuth and a manual "Authorization" header for the FHIR server — they would conflict. Use a different header name (e.g. X-Api-Key) for the manual header, or configure only one auth mechanism.');
        }
    }
}

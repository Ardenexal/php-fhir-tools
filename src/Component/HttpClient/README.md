# FHIR HTTP Client

Shared FHIR server transport for the toolkit: base-URL joining, FHIR JSON headers, status handling, and
graceful-null error degradation, plus an offline `application/x-fhir-query` template resolver.

**Namespace:** `Ardenexal\FHIRTools\Component\HttpClient\`

```bash
composer require ardenexal/fhir-http-client
```

## Status

Delivered by the `x-fhir-query` feature plan (`.goat-flow/plans/x-fhir-query/`). Houses the FHIR search
client used by SDC `$populate`'s opt-in live x-fhir-query path, and the relocated terminology client
(previously stranded in `Validation`).

## The two client shapes

`FHIRHttpClientInterface` (`Metadata`) exposes two operations, both backed by the same transport core:

- **`search(string $search, string $fhirVersion): ?BundleResource`** — execute a resolved FHIR search
  string and get back a **typed** `Bundle` for the requested FHIR version, or `null` on any failure
  (transport error, non-2xx status, malformed/unexpected body).
- **`request(string $method, string $path, ?string $body = null, array $headers = []): ?string`** —
  the lower-level escape hatch: an arbitrary FHIR REST call (e.g. `$validate-code`) returning the raw
  response body, or `null` on failure. `search()` is built on top of this.
- **`followLink(string $url, string $fhirVersion): ?BundleResource`** — follow a server-supplied
  `Bundle.link.url` (e.g. `relation = 'next'`) and get back a typed `Bundle` page, or `null` when the URL
  is cross-origin (rejected) or on any transport/HTTP/parse failure. See **Multi-page result following**
  below.

```php
$client = new FHIRHttpClient($symfonyHttpClient, 'https://fhir.example.org/r4');

$bundle = $client->search('Observation?subject=Patient/123&status=final', 'R4');
// $bundle is a typed BundleResource, or null (server unreachable, timed out, 4xx/5xx, bad body)
```

### Multi-page result following

A searchset that spans more than one page carries a `Bundle.link` entry with `relation = 'next'`. Callers
that need every result — not just the first page — follow it via `followLink()`:

```php
$page = $client->search('Observation?subject=Patient/123', 'R4');
while ($page !== null) {
    // ... collect $page's entries ...
    $nextUrl = /* extract link.where(relation = 'next').url from $page, e.g. via FHIRPath */;
    $page    = $nextUrl !== null ? $client->followLink($nextUrl, 'R4') : null;
}
```

`XFhirQueryPopulationDataProvider` (Sdc) does exactly this for x-fhir-query population, bounded to 50
pages — see its class docblock for the bound's rationale.

**Same-origin only.** Unlike a `search()` search string (which the resolver builds and can never carry a
foreign host — see *Security posture* below), a `next` link's URL is server-supplied and absolute. If it
pointed anywhere the client wanted, that would reopen exactly the SSRF gap `search()` closes structurally.
`followLink()` rejects (returns `null` for) any URL whose origin — scheme, host, and port — doesn't match
the client's own configured `baseUrl`, or whose path isn't under `baseUrl`'s own path prefix (a path
outside that prefix can't be safely re-expressed through the fixed-authority `request()` join without
either duplicating or losing the prefix). A well-behaved FHIR server always echoes links under its own
configured endpoint, so this holds for normal deployments; it does not accommodate a server deliberately
proxied behind a different public host than the one configured as `baseUrl`.

**Partial-failure posture.** If a later page's fetch fails — off-host rejection, transport error, or simply
no further `next` link — pagination stops there and whatever pages were fetched successfully are returned.
This mirrors the graceful-degradation posture used throughout this transport stack (a fetch failure
degrades to `null`/omission, never a distinct error signal); only the **first** page's failure is
distinguished (as `resourcesForQuery()` returning `null`, meaning the whole search failed).

### Authentication

`FHIRHttpClient` takes no auth parameters — it doesn't need to. Auth is a per-request header concern the
caller configures on the injected `HttpClientInterface`, using Symfony's own scoping:

```php
$authed = $symfonyHttpClient->withOptions(['auth_bearer' => $token]); // or 'auth_basic' => 'user:pass'
$client = new FHIRHttpClient($authed, 'https://fhir.example.org/r4');
```

Every request `FHIRHttpClient` makes goes through this scoped client, so the header is applied
consistently with no new API surface here.

The `OAuth\` subnamespace builds on this same pattern for two cases `withOptions()` alone doesn't cover —
a token that needs to be *fetched and refreshed*, and a header whose name isn't `Authorization`:

- **`OAuth\OAuthClientCredentialsTokenProvider`** — fetches an OAuth 2.0 client-credentials-grant access
  token (RFC 6749 §4.4) from a token endpoint, optionally caching it (PSR-6) with a TTL derived from the
  server's own `expires_in`. **`OAuth\OAuthHttpClient`** decorates an `HttpClientInterface` with it,
  attaching a fresh (or cached) bearer token to every request via `auth_bearer` — no changes to
  `FHIRHttpClient` needed:
  ```php
  $tokenProvider = new OAuthClientCredentialsTokenProvider($symfonyHttpClient, $tokenUrl, $clientId, $clientSecret);
  $authed        = new OAuthHttpClient($symfonyHttpClient, $tokenProvider);
  $client        = new FHIRHttpClient($authed, 'https://fhir.example.org/r4');
  ```
  A token-fetch failure never exposes the client secret: `OAuthTokenException`'s message is always a
  fixed, generic string, and `OAuthHttpClient` rethrows it as a Symfony `TransportException` so it flows
  through `FHIRHttpClient`'s existing graceful-null handling like any other transport failure.
- **`OAuth\StaticHeaderHttpClient`** — attaches one fixed header (name + value) to every request
  verbatim, no token exchange. Covers a hand-obtained bearer token or an arbitrary header like
  `X-Api-Key`:
  ```php
  $authed = new StaticHeaderHttpClient($symfonyHttpClient, 'X-Api-Key', $apiKey);
  $client = new FHIRHttpClient($authed, 'https://fhir.example.org/r4');
  ```

Both decorators are composable (they touch different request options) as long as they don't target the
same header name — see `demo/src/Sdc/ExternalClientFactory.php` for the demo's wiring, including the
misconfiguration guards for that case.

### Null-object default

`NullFHIRHttpClient` performs no I/O and returns `null` for every call. It is the wiring default when no
live FHIR server is configured, so any feature that can *optionally* fetch from a server (SDC
`$populate`'s x-fhir-query path today) behaves exactly as if the fetch found nothing — no network
dependency, no special-casing at the call site.

### Offline-first note

Nothing in this component makes an outbound request unless the caller explicitly constructs a
`FHIRHttpClient` with a real `HttpClientInterface` and base URL and passes it in. The default across the
toolkit (e.g. `PopulateContext::$queryProvider = null`) stays fully offline.

## The `application/x-fhir-query` resolver

`XFhirQuery\XFhirQueryResolver` is a **pure, offline** template resolver: it turns a FHIR search string
with embedded `{{ fhirpath }}` holes into a concrete search string, evaluating each hole against a
FHIRPath `EvaluationContext` (launch-context resources bound as `%patient`, `%user`, …) per the
[R5 x-fhir-query spec](https://hl7.org/fhir/R5/fhir-xquery.html). It performs no network I/O — executing
the resolved string against a server is `FHIRHttpClient`'s job.

```php
$resolver = new XFhirQueryResolver();
$search   = $resolver->resolve('Observation?subject={{%patient.id}}&status=final', $evalContext);
// 'Observation?subject=123&status=final'

$bundle = $client->search($search, 'R4');
```

**Empty-substitution policy:** a search parameter whose value contains a hole that resolves to empty is
**dropped entirely** (this widens the query — see the resolver's class docblock); if every parameter
drops, the `?` itself is omitted rather than emitting the fetch-everything form (`Observation?`).

## Security posture

- **SSRF (resolved search strings).** Every evaluated leaf value is percent-encoded before substitution
  (`XFhirQueryResolver::encode()`), so launch-context data can never inject an unescaped scheme, host, or
  path separator into the resolved search string. `FHIRHttpClient::request()` always joins the resolved
  search onto the caller-configured `baseUrl` (`rtrim($baseUrl, '/') . '/' . ltrim($path, '/')`) — the
  base URL is always the request's authority; the search string can only ever land in its path/query,
  never redirect to a different host. See `FHIRQuestionnaireXFhirQueryPopulateTest` and
  `XFhirQueryResolverTest` / `FHIRHttpClientTest` for the tests proving this.
- **SSRF (server-supplied `next` links).** A `Bundle.link.url` is absolute and server-supplied, so the
  percent-encoding guarantee above doesn't apply to it. `followLink()` closes this the same way `search()`
  closes the resolved-string case, just checked at the origin level instead of by construction: same-origin
  URLs are followed, everything else is rejected. See *Multi-page result following* above and
  `FHIRHttpClientTest::testFollowLinkRejectsCrossOriginHost` / `...Scheme` / `...Port`.
- **PHI / access control.** This library fetches whatever the configured server returns for the resolved
  search and applies **no** permission filtering — deciding what a given user may see, and constraining
  the query (or the launch context that parameterises it) accordingly, is the caller's responsibility.
  Same posture as SDC `$populate`/`$extract` (ADR-011 Decision 5), extended here to live-fetched data.
- **Timeouts / unavailability.** A transport error, timeout, non-2xx status, or malformed body all
  degrade to `null` — never an uncaught exception.

Full rationale: `.goat-flow/learning-loop/decisions/ADR-013-x-fhir-query-http-client-boundaries.md`.

## License

Released under the MIT License. See [LICENSE](../../../LICENSE).

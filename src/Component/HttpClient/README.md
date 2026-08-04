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

```php
$client = new FHIRHttpClient($symfonyHttpClient, 'https://fhir.example.org/r4');

$bundle = $client->search('Observation?subject=Patient/123&status=final', 'R4');
// $bundle is a typed BundleResource, or null (server unreachable, timed out, 4xx/5xx, bad body)
```

### Authentication

`FHIRHttpClient` takes no auth parameters — it doesn't need to. Auth is a per-request header concern the
caller configures on the injected `HttpClientInterface`, using Symfony's own scoping:

```php
$authed = $symfonyHttpClient->withOptions(['auth_bearer' => $token]); // or 'auth_basic' => 'user:pass'
$client = new FHIRHttpClient($authed, 'https://fhir.example.org/r4');
```

Every request `FHIRHttpClient` makes goes through this scoped client, so the header is applied
consistently with no new API surface here.

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

- **SSRF.** Every evaluated leaf value is percent-encoded before substitution
  (`XFhirQueryResolver::encode()`), so launch-context data can never inject an unescaped scheme, host, or
  path separator into the resolved search string. `FHIRHttpClient::request()` always joins the resolved
  search onto the caller-configured `baseUrl` (`rtrim($baseUrl, '/') . '/' . ltrim($path, '/')`) — the
  base URL is always the request's authority; the search string can only ever land in its path/query,
  never redirect to a different host. See `FHIRQuestionnaireXFhirQueryPopulateTest` and
  `XFhirQueryResolverTest` / `FHIRHttpClientTest` for the tests proving this.
- **PHI / access control.** This library fetches whatever the configured server returns for the resolved
  search and applies **no** permission filtering — deciding what a given user may see, and constraining
  the query (or the launch context that parameterises it) accordingly, is the caller's responsibility.
  Same posture as SDC `$populate`/`$extract` (ADR-011 Decision 5), extended here to live-fetched data.
- **Timeouts / unavailability.** A transport error, timeout, non-2xx status, or malformed body all
  degrade to `null` — never an uncaught exception.

Full rationale: `.goat-flow/learning-loop/decisions/ADR-013-x-fhir-query-http-client-boundaries.md`.

## License

Released under the MIT License. See [LICENSE](../../../LICENSE).

---
description: Call a FHIR server from PHP, with typed search results, OAuth and x-fhir-query templates.
icon: globe
---

# Overview

`ardenexal/fhir-http-client` is the toolkit's client for talking to a FHIR server. Other packages
make their FHIR server calls through it:

* SDC `$populate` fetches live `application/x-fhir-query` context with it. See
  [SDC](../sdc/overview.md).
* FHIRPath `resolve()` uses it for relative and canonical references, and `memberOf()` for value-set
  checks. A reference's own absolute URL is fetched with a separate PSR-18 client instead. See
  [FHIR-Specific functions](../fhirpath/functions/fhir.md).
* The HTTP terminology clients for binding validation live in this package. See
  [Validation configuration](../validation/configuration.md).

```bash
composer require ardenexal/fhir-http-client symfony/http-client
```

The package depends only on the Symfony HTTP client contracts. The examples on this page use
`symfony/http-client` as the implementation; any `HttpClientInterface` implementation works.

No FHIR server is called unless you construct a `FHIRHttpClient` with a real HTTP client and pass it
in.

## `FHIRHttpClient`

`FHIRHttpClient` wraps a Symfony `HttpClientInterface` and one server base URL. It implements
`FHIRHttpClientInterface` from `fhir-metadata`:

| Method | Returns |
|---|---|
| `search(string $search, string $fhirVersion)` | The search result as a typed `Bundle` model for `'R4'`, `'R4B'` or `'R5'` |
| `followLink(string $url, string $fhirVersion)` | The page a `Bundle.link.url` points at, as a typed `Bundle` |
| `request(string $method, string $path, ?string $body = null, array $headers = [])` | The raw response body, for any other FHIR REST call such as `$validate-code` |

Each method returns `null` when the call fails: a transport error, a timeout, a non-2xx status or a
body it cannot read. `search()` and `followLink()` throw a `\ValueError` for an unknown FHIR version,
because that is a programming error rather than a server failure.

```php
use Ardenexal\FHIRTools\Component\HttpClient\FHIRHttpClient;
use Symfony\Component\HttpClient\HttpClient;

$client = new FHIRHttpClient(HttpClient::create(), 'https://fhir.example.org/r4');

$bundle = $client->search('Observation?subject=Patient/123&status=final', 'R4');
if ($bundle === null) {
    // The server was unreachable, timed out, answered 4xx/5xx, or returned a body that is not a Bundle
}
```

The search string is always joined onto the configured base URL, so it can only become the path and
query of the request, never a different host.

### Following result pages

A search result that spans several pages carries a `Bundle.link` with `relation = 'next'`. Pass its URL
to `followLink()` to fetch the next page:

```php
$page = $client->search('Observation?subject=Patient/123', 'R4');
while ($page !== null) {
    // ... read $page's entries ...
    $nextUrl = /* the url of $page's link where relation = 'next', or null */;
    $page    = $nextUrl !== null ? $client->followLink($nextUrl, 'R4') : null;
}
```

`followLink()` follows only URLs on the client's own server: the scheme, host and port must match the
base URL, and the path must sit under the base URL's path. Any other URL returns `null`, so a server
cannot point the client at another host.

### Authentication

`FHIRHttpClient` sends every request through the Symfony client you give it, so configure
authentication on that client:

```php
$authed = HttpClient::create()->withOptions(['auth_bearer' => $token]); // or 'auth_basic' => 'user:pass'
$client = new FHIRHttpClient($authed, 'https://fhir.example.org/r4');
```

Two decorators in the `OAuth\` namespace cover the cases `withOptions()` does not:

| Class | Use it for |
|---|---|
| `OAuth\OAuthHttpClient` with `OAuth\OAuthClientCredentialsTokenProvider` | An OAuth 2.0 client-credentials token fetched from a token endpoint. Pass a PSR-6 pool to the provider to share the token across requests, cached until 60 seconds before the server's `expires_in` |
| `OAuth\StaticHeaderHttpClient` | One fixed header on every request, such as a token you already hold or `X-Api-Key` |

```php
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthClientCredentialsTokenProvider;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\OAuthHttpClient;
use Ardenexal\FHIRTools\Component\HttpClient\OAuth\StaticHeaderHttpClient;

$http = HttpClient::create();

$tokens = new OAuthClientCredentialsTokenProvider($http, $tokenUrl, $clientId, $clientSecret);
$client = new FHIRHttpClient(new OAuthHttpClient($http, $tokens), 'https://fhir.example.org/r4');

$client = new FHIRHttpClient(
    new StaticHeaderHttpClient($http, 'X-Api-Key', $apiKey),
    'https://fhir.example.org/r4',
);
```

A provider fetches its token once and then keeps it for its own lifetime. It does not check expiry
and does not retry on a `401`, so a provider that lives longer than its token keeps sending the
expired one. Build the provider and its `OAuthHttpClient` per request or job, and pass a PSR-6 pool
so that each new provider reuses the cached token until it is due to expire.

When a token cannot be fetched, the request fails like any other transport error, so the
`FHIRHttpClient` method returns `null`. The error message never contains the client secret.

## `NullFHIRHttpClient`

`NullFHIRHttpClient` makes no requests and returns `null` from every method. Use it where a client is
required but no server is configured. The Symfony bundle registers it as the default
`FHIRHttpClientInterface`. To use a real server, install `symfony/http-client` so the bundle can
autowire an `HttpClientInterface`, then register `FHIRHttpClient` in your application's
`services.yaml` and point the alias at it:

```yaml
services:
    Ardenexal\FHIRTools\Component\HttpClient\FHIRHttpClient:
        arguments:
            $baseUrl: '%env(FHIR_SERVER_URL)%'
    Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface:
        alias: 'Ardenexal\FHIRTools\Component\HttpClient\FHIRHttpClient'
```

## Resolving x-fhir-query templates

`XFhirQuery\XFhirQueryResolver` turns an
[x-fhir-query](https://hl7.org/fhir/R5/fhir-xquery.html) template into a search string. Each
`{{ … }}` hole is a FHIRPath expression evaluated against an `EvaluationContext`, with launch-context
resources bound as external constants such as `%patient`. The resolver makes no request; pass its
output to `search()`.

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\HttpClient\XFhirQuery\XFhirQueryResolver;

$context = (new EvaluationContext())->withExternalConstant('patient', $patient);
$search  = (new XFhirQueryResolver())->resolve('Observation?subject={{%patient.id}}&status=final', $context);
// 'Observation?subject=123&status=final'

$bundle = $client->search($search, 'R4');
```

Substituted values are percent-encoded, except a token's system URI and the `|` and `,` separators,
which are emitted as written. A search parameter whose hole resolves to nothing is
dropped from the query, which widens the search: `Observation?subject={{%patient.id}}` with a
`%patient` that has no `id` becomes `Observation`, a search for every subject's observations. When
every parameter is dropped, the `?` is dropped too. An unterminated `{{` throws
`\InvalidArgumentException`, and a hole naming a constant the context does not bind throws the FHIRPath
`EvaluationException`.

{% hint style="warning" %}
The client returns whatever the server sends for a search. It applies no access control, so deciding
what a user may see, and constraining the query or launch context to match, is up to the caller.
{% endhint %}

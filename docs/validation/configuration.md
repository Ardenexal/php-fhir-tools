---
description: Configure terminology clients, reference resolvers, and obligation contexts.
icon: gear
---

# Configuration

Several validation concerns rely on pluggable services you wire yourself. Each ships with a
null object so the feature degrades gracefully (silently skipped, or surfaced as an INFO
violation) until you provide a real implementation.

## Terminology clients

`FHIRTerminologyClientInterface` controls validation of extensible and preferred value-set
bindings. It defines three methods: `validateCode()`, `validateCoding()`, and
`validateCodingWithDisplay()` (the last returns a `CodingValidationResult` carrying a validity
flag plus an optional corrected display string).

| Class | Role |
|---|---|
| `HttpFHIRTerminologyClient` | Calls a terminology server's `ValueSet/$validate-code` operation (GET by default, POST optional). Returns `false` / invalid on any HTTP, transport, or parse error (graceful degradation). |
| `CachingFHIRTerminologyClient` | Decorator caching results. An in-process array cache is always active; a PSR-6 pool is optional for cross-request persistence. |
| `NullFHIRTerminologyClient` | Null object — treats every code as valid. Equivalent to having no client: extensible/preferred checks are skipped. |
| `PreferredServerAwareTerminologyClient` | Tries an ordered list of preferred clients, failing over to a fallback. Only a `\Throwable` triggers failover — a definitive `false` is final. |
| `HttpFHIRTerminologyClientFactory` | Builds `HttpFHIRTerminologyClient` instances per server base URL (`createForServer()`), wrapping in a caching decorator when a pool is configured. |

{% hint style="warning" %}
`HttpFHIRTerminologyClient`'s constructor takes the HTTP client **first**, then the server URL:
`__construct(HttpClientInterface $httpClient, string $serverUrl, bool $usePost = false)`. There
is no `baseUrl` parameter.
{% endhint %}

{% tabs %}
{% tab title="Production (HTTP)" %}
```php
use Ardenexal\FHIRTools\Component\Validation\HttpFHIRTerminologyClient;

$terminologyClient = new HttpFHIRTerminologyClient(
    httpClient: $symfonyHttpClient,
    serverUrl: 'https://tx.fhir.org/r4',
);
```
{% endtab %}

{% tab title="Development / offline" %}
```php
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;

// Skips extensible/preferred checks; each skip becomes a fhir:unchecked-binding INFO violation.
$terminologyClient = new NullFHIRTerminologyClient();
```
{% endtab %}

{% tab title="Cached" %}
```php
use Ardenexal\FHIRTools\Component\Validation\CachingFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\HttpFHIRTerminologyClient;

$terminologyClient = new CachingFHIRTerminologyClient(
    new HttpFHIRTerminologyClient($symfonyHttpClient, 'https://tx.fhir.org/r4'),
    cache: $psr6Pool, // optional; in-process cache always active without it
    ttl: 3600,        // 0 = no expiry
);
```
{% endtab %}

{% tab title="Preferred + fallback" %}
```php
use Ardenexal\FHIRTools\Component\Validation\CachingFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\HttpFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\PreferredServerAwareTerminologyClient;

$terminologyClient = new PreferredServerAwareTerminologyClient(
    preferred: [
        new CachingFHIRTerminologyClient(
            new HttpFHIRTerminologyClient($symfonyHttpClient, 'https://preferred.example.org/r4'),
            cache: $psr6Pool,
        ),
    ],
    fallback: new CachingFHIRTerminologyClient(
        new HttpFHIRTerminologyClient($symfonyHttpClient, 'https://tx.fhir.org/r4'),
        cache: $psr6Pool,
    ),
);
```
{% endtab %}
{% endtabs %}

When the client is `null` or a `NullFHIRTerminologyClient`, no violation is raised for invalid
codes against extensible or preferred value sets. Instead each skipped check emits a single
`fhir:unchecked-binding` INFO violation surfacing the coverage gap:

```php
$report = $service->validate($patient);

if ($report->hasUncheckedBindings()) {
    foreach ($report->uncheckedBindings() as $unchecked) {
        echo $unchecked->message . "\n";
    }
}
```

{% hint style="info" %}
The `HttpClientInterface` is Symfony's HTTP client contract
(`Symfony\Contracts\HttpClient\HttpClientInterface`), not PSR-18. The cache pool is a PSR-6
`Psr\Cache\CacheItemPoolInterface`.
{% endhint %}

## Reference resolvers

`FHIRReferenceResolverInterface` controls whether target-profile constraints on `Reference`
properties are enforced. A resolver receives a Reference object and returns the in-process PHP
object it points to, or `null`.

* `NullFHIRReferenceResolver` — the default null object; always returns `null`, so target-profile
  checks are silently skipped.
* Custom implementations may inspect `Reference::$reference` for a local `#id` fragment and walk
  a Bundle's contained resources.

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;

$resolver = new class($bundle) implements FHIRReferenceResolverInterface {
    public function __construct(private readonly object $bundle) {}

    public function resolve(object $reference): ?object
    {
        $ref = (string) ($reference->reference ?? '');
        if (!str_starts_with($ref, '#')) {
            return null; // only local contained references
        }
        $id = ltrim($ref, '#');
        foreach ($this->bundle->contained ?? [] as $resource) {
            if (($resource->id ?? null) === $id) {
                return $resource;
            }
        }
        return null;
    }
};
```

## Type hierarchy resolvers

`FHIRTypeHierarchyResolverInterface` controls whether bare-type (e.g. `"HumanName"`,
`"DomainResource"`) and foreign-root type-path extension contexts are evaluated against the FHIR
type of the element they apply to. It exposes `resolvePropertyType()` and `resolveTypeHierarchy()`.

* `FhirPropertyTypeHierarchyResolver` — the **default** (it is the `typeResolver` constructor
  default on `FHIRValidationService`). Reads `fhirType` from `#[FhirProperty]` attributes and
  walks the generated class inheritance chain via `#[FhirResource]`/`#[FHIRComplexType]`. No
  codegen changes required; results are cached per class.
* `NullFHIRTypeHierarchyResolver` — returns `null` / an empty list, deferring these contexts
  (never produces a violation).

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTypeHierarchyResolver;

// Default resolver is used when typeResolver is omitted.
$service = new FHIRValidationService(
    $validator,
    $pathService,
    typeResolver: new NullFHIRTypeHierarchyResolver(), // opt out
);
```

{% hint style="info" %}
Resolution is monotonic: a context naming a supertype permits its subtypes, and wiring the
resolver only ever clears false positives — it never introduces a denial. It is safe to leave on.
{% endhint %}

## Obligation contexts

`FHIRObligationContext` scopes obligation validation to a specific actor. It is constructed with
a single `?string $actorUrl` and is opt-in — pass it to `FHIRValidationService::validate()` to
activate obligation checks.

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRObligationContext;

$context = new FHIRObligationContext(
    actorUrl: 'http://hl7.org/fhir/uv/ips/ActorDefinition/Creator',
);

$report = $service->validate(
    $resource,
    profileUrls: ['http://hl7.org/fhir/uv/ips/StructureDefinition/Patient-uv-ips'],
    obligationContext: $context,
);
```

Obligations without a declared actor match every context (`matchesObligation()` returns `true`).
Obligations with a specific actor fire only when `actorUrl` matches. Pass
`new FHIRObligationContext(null)` to match only actor-less obligations.

See [Validation Reports & Violation Codes](reports.md) for the report and violation structures.

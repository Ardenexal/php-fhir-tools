<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\BundleEntryScope;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\BundleIndex;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * resolve(): collection
 *
 * FHIR R4 FHIRPath extension function.
 *
 * Resolves each item in the input collection to the FHIR resource it references.
 * Items that cannot be resolved are silently omitted (resolution failure is not
 * an error per the spec).
 *
 * Input item types handled:
 *  - FHIR Reference object/array (has a `reference` property)
 *  - Plain string (URI, canonical, or relative reference)
 *  - Either of those behind a FHIR wrapper (StringPrimitive, FHIRTypedCollection, ...)
 *
 * A resolved resource keeps the shape it was found in: a model object when it came from a model's
 * `contained` or `Bundle.entry.resource`, a decoded array when it came from an array or over HTTP.
 *
 * When the input was navigated out of a Bundle entry, or the root resource is a Bundle, the entries
 * are consulted first, as the spec requires (https://hl7.org/fhir/R4/bundle.html#references):
 *
 *  - Absolute reference (any URI scheme, including `urn:uuid:`): the entry with that `fullUrl`.
 *  - Relative `Type/id`: applied to the root of the referring entry's RESTful `fullUrl`, then matched
 *    the same way. Under a non-RESTful `fullUrl` the Bundle gives it no meaning.
 *  - Version-specific (`.../_history/v`): matched without the version, then on `meta.versionId`.
 *  - Fragment-only (`#id`): the referring entry's own `contained`; never another entry's.
 *
 * Several matches are ambiguous, so the reference is omitted rather than guessed at or fetched. No
 * match falls through to the strategies below.
 *
 * The referring entry comes from {@see BundleEntryScope}. A value merged from several entries resolves
 * only if they all reach one resource. A value with no origin (a literal, $total, an evaluation started
 * inside the entry) has the entries holding that reference value stand in for the referring entry, and
 * resolves only if they all agree and the root holds no nested Bundle.
 *
 * Remaining resolution strategies (in order, mirroring the reference JavaScript engine):
 *
 *  1. Fragment-only (`#id`):
 *       Looks in `rootResource['contained']` for an item with matching `id`.
 *       Does NOT require a FHIR server URL or HTTP client.
 *
 *  2. Absolute URL (`https?://...`):
 *       a. Canonical URL (contains `|`, or `isCanonical=true`):
 *            Queries `{fhirServerUrl}/{refType}?url={url}&version={version}` as
 *            a FHIR search, then extracts `entry[0].resource`.
 *            Requires fhirServerUrl + httpClient + refType.
 *       b. Plain absolute URL:
 *            Fetches the URL directly via the HTTP client.
 *            Requires httpClient.
 *
 *  3. Relative URL (`ResourceType/id`):
 *       Prepends the FHIR server URL and fetches.
 *       Requires fhirServerUrl + httpClient.
 *       Only attempted when the URL starts with an uppercase letter (FHIR
 *       resource type convention), mirroring the JS `baseResourceTypes` check.
 *
 *  4. URL with fragment (`someUrl#id`):
 *       Fetches the base resource (via strategies 2/3), then finds the
 *       contained resource with matching `id`.
 *
 * Configuration on FHIRPathEvaluator:
 *   $evaluator->setFhirServerUrl('https://r4.smarthealthit.org');
 *   $evaluator->setHttpClient($psr18Client, $psr17RequestFactory);
 *
 * Does NOT depend on Models or CodeGeneration.
 *
 * Spec reference: FHIR R4 FHIRPath supplement §2.3
 *
 * @phpstan-type ResolvedResource array{array<array-key, mixed>|object, BundleEntryScope|null}
 *
 * @author FHIR Tools Contributors
 */
final class ResolveFunction extends AbstractFunction
{
    /**
     * The RESTful URL regex from https://hl7.org/fhir/R4/references.html#regex, capturing the root.
     * The resource type is a shape rather than the spec's per-release list of names, so one
     * expression serves every FHIR version.
     */
    private const RESTFUL_FULL_URL = '#^(https?://(?:[A-Za-z0-9\-\\\\.:%$]*/)+)[A-Z][A-Za-z]+/[A-Za-z0-9\-.]{1,64}(?:/_history/[A-Za-z0-9\-.]{1,64})?$#';

    private const ABSOLUTE_REFERENCE = '/^[A-Za-z][A-Za-z0-9+.\-]*:/';

    private const RELATIVE_REFERENCE = '#^[A-Z][A-Za-z]+/[A-Za-z0-9\-.]{1,64}(?:/_history/[A-Za-z0-9\-.]{1,64})?$#';

    public function __construct()
    {
        parent::__construct('resolve');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context', 0, 0);
        }

        $fhirServerUrl  = $evaluator->getFhirServerUrl();
        $httpClient     = $evaluator->getHttpClient();
        $requestFactory = $evaluator->getRequestFactory();

        $rootIndex = $evaluator->getRootBundleIndex($context->getRootResource());

        $resolved = [];
        $scopes   = [];
        foreach ($input as $index => $item) {
            $match = $this->resolveItem($item, $input->scopeAt($index), $rootIndex, $context, $fhirServerUrl, $httpClient, $requestFactory);
            // Model objects are only ever read from `contained` and `Bundle.entry.resource`, which
            // hold nothing but resources.
            if ($match !== null && (is_object($match[0]) || isset($match[0]['resourceType']))) {
                // A reference on the resolved resource belongs to the entry it was found in.
                if ($match[1] !== null) {
                    $scopes[count($resolved)] = $match[1];
                }
                $resolved[] = $match[0];
            }
        }

        return Collection::from($resolved)->withScopes($scopes);
    }

    /**
     * Resolve a single input item to a FHIR resource.
     *
     * Mirrors the JS `engine.resolveFn` dispatch logic:
     *  - FHIR.Reference (array/object with `reference` key) → use reference URL
     *  - String (uri, canonical, FHIR.string) → use as URL directly
     *
     * @param BundleEntryScope|null        $scope          The Bundle entry the item was navigated out of
     * @param BundleIndex|null             $rootIndex      The root resource, when that is a Bundle
     * @param EvaluationContext            $context        Evaluation context (root resource, value normalisation)
     * @param string|null                  $fhirServerUrl  Base FHIR server URL (may be null)
     * @param ClientInterface|null         $httpClient     PSR-18 HTTP client (may be null)
     * @param RequestFactoryInterface|null $requestFactory PSR-17 request factory (may be null)
     *
     * @return ResolvedResource|null FHIR resource and the Bundle entry it was found in, or null if unresolvable
     */
    private function resolveItem(mixed $item, ?BundleEntryScope $scope, ?BundleIndex $rootIndex, EvaluationContext $context, ?string $fhirServerUrl, ?ClientInterface $httpClient, ?RequestFactoryInterface $requestFactory): ?array
    {
        // Sees through StringPrimitive/UriPrimitive (a plain reference string on a model) and
        // FHIRTypedCollection (a raw Reference reached through a polymorphic `value`).
        $item = $context->normalizeValue($item);

        // FHIR.Reference: array or object with a 'reference' property
        if (is_array($item) && isset($item['reference']) && is_string($item['reference'])) {
            $refType = isset($item['type']) && is_string($item['type']) ? $item['type'] : null;

            return $this->resolveUrl($item['reference'], false, $refType, $scope, $rootIndex, $context, $fhirServerUrl, $httpClient, $requestFactory);
        }

        if (is_object($item)) {
            $ref = $context->normalizeValue($this->getObjectProperty($item, 'reference'));
            if (is_string($ref)) {
                $refType = $context->normalizeValue($this->getObjectProperty($item, 'type'));
                $refType = is_string($refType) ? $refType : null;

                return $this->resolveUrl($ref, false, $refType, $scope, $rootIndex, $context, $fhirServerUrl, $httpClient, $requestFactory);
            }
        }

        // Plain string: uri, canonical, or System.String
        if (is_string($item)) {
            return $this->resolveUrl($item, false, null, $scope, $rootIndex, $context, $fhirServerUrl, $httpClient, $requestFactory);
        }

        return null;
    }

    /**
     * Resolve a URL string to a FHIR resource.
     *
     * Direct port of the JS `requestResourceByUrl()` function.
     *
     * @param string                       $url            The reference URL (may contain `#` fragment)
     * @param bool                         $isCanonical    True when the URL is a canonical reference
     * @param string|null                  $refType        FHIR resource type hint (e.g. 'Patient')
     * @param BundleEntryScope|null        $scope          The Bundle entry the reference was navigated out of
     * @param BundleIndex|null             $rootIndex      The root resource, when that is a Bundle
     * @param EvaluationContext            $context        Evaluation context (root resource, value normalisation)
     * @param string|null                  $fhirServerUrl  Base FHIR server URL
     * @param ClientInterface|null         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface|null $requestFactory PSR-17 request factory
     *
     * @return ResolvedResource|null
     */
    private function resolveUrl(string $url, bool $isCanonical, ?string $refType, ?BundleEntryScope $scope, ?BundleIndex $rootIndex, EvaluationContext $context, ?string $fhirServerUrl, ?ClientInterface $httpClient, ?RequestFactoryInterface $requestFactory): ?array
    {
        if ($scope?->origins !== null) {
            return $this->resolveInEveryOrigin($url, $isCanonical, $refType, $scope->origins, $rootIndex, $context, $fhirServerUrl, $httpClient, $requestFactory);
        }

        // Split on first '#' to separate base URL from fragment (contained resource ID)
        $hashPos  = strpos($url, '#');
        $baseUrl  = $hashPos !== false ? substr($url, 0, $hashPos) : $url;
        $fragment = $hashPos !== false ? substr($url, $hashPos + 1) : '';

        $bundleIndex = $scope->index ?? $rootIndex;
        if ($bundleIndex !== null) {
            $matches = $this->resolveInBundle($url, $baseUrl, $fragment, $bundleIndex, $scope, $context);
            if ($matches !== null) {
                if (count($matches) !== 1) {
                    return null;
                }

                [$resource, $targetScope] = $matches[0];
                if ($baseUrl === '' || $fragment === '') {
                    return $matches[0];
                }

                $contained = $this->getContainedResource($resource, $fragment, $context);

                return $contained === null ? null : [$contained, $targetScope];
            }
        }

        $resource = null;

        if (preg_match('/^https?:\/\//', $baseUrl) === 1) {
            // ── Absolute URL ──────────────────────────────────────────────
            if (str_contains($baseUrl, '|') || $isCanonical) {
                // Canonical URL: query FHIR server with ?url=...&version=...
                if ($refType !== null) {
                    $resource = $this->fetchCanonical($baseUrl, $refType, $fhirServerUrl, $httpClient, $requestFactory);
                }
            } elseif ($refType !== null && $httpClient !== null) {
                // Absolute URL with known resource type: fetch directly, fall back to canonical
                $resource = $this->fetch($baseUrl, $httpClient, $requestFactory);
                if ($resource === null) {
                    $resource = $this->fetchCanonical($baseUrl, $refType, $fhirServerUrl, $httpClient, $requestFactory);
                }
            } else {
                // Absolute URL without type hint: fetch directly
                $resource = $this->fetch($baseUrl, $httpClient, $requestFactory);
            }
        } elseif ($baseUrl !== '') {
            // ── Relative URL (e.g. "Patient/123") ────────────────────────
            // Only resolve relative URLs that look like FHIR resource references
            // (start with an uppercase letter — the FHIR ResourceType convention).
            // This mirrors the JS `ctx.model.type2Parent[type] in baseResourceTypes` check.
            if (preg_match('/^[A-Z][A-Za-z]+\//', $baseUrl) === 1 && $fhirServerUrl !== null && $httpClient !== null) {
                $resource = $this->fetch($fhirServerUrl . '/' . ltrim($baseUrl, '/'), $httpClient, $requestFactory);
            }
        }
        // else: fragment-only URL — handled below

        if ($resource === null && $baseUrl === '' && $fragment !== '') {
            // Fragment-only reference (#id) → search rootResource.contained
            $contained = $this->getContainedResource($context->getRootResource(), $fragment, $context);

            return $contained === null ? null : [$contained, null];
        }

        if ($resource === null) {
            return null;
        }

        // A fetched resource is not part of any Bundle in play, so its own references must not be
        // answered from one: an empty index makes it its own context, with the URL as its `fullUrl`.
        $fetchedScope = new BundleEntryScope(new BundleIndex([]), ['fullUrl' => $baseUrl, 'resource' => $resource], 'fetched:' . $baseUrl);

        if ($fragment === '') {
            return [$resource, $fetchedScope];
        }

        // URL + fragment → fetch resource, then find contained by ID
        $contained = $this->getContainedResource($resource, $fragment, $context);

        return $contained === null ? null : [$contained, $fetchedScope];
    }

    /**
     * Resolve a value that was merged from several origins.
     *
     * The value means something in each of them, so it only resolves if they all reach one resource.
     * An absolute reference means the same throughout a Bundle, so one Bundle needs asking only once.
     *
     * @param list<BundleEntryScope|null> $origins
     *
     * @return ResolvedResource|null
     */
    private function resolveInEveryOrigin(string $url, bool $isCanonical, ?string $refType, array $origins, ?BundleIndex $rootIndex, EvaluationContext $context, ?string $fhirServerUrl, ?ClientInterface $httpClient, ?RequestFactoryInterface $requestFactory): ?array
    {
        $isAbsolute = preg_match(self::ABSOLUTE_REFERENCE, $url) === 1;
        $asked      = [];
        $resource   = null;
        $scopes     = [];

        foreach ($origins as $origin) {
            $key = $isAbsolute && $origin !== null ? spl_object_id($origin->index) : ($origin->path ?? '');
            if (isset($asked[$key])) {
                continue;
            }
            $asked[$key] = true;

            $match = $this->resolveUrl($url, $isCanonical, $refType, $origin, $rootIndex, $context, $fhirServerUrl, $httpClient, $requestFactory);
            if ($match === null || ($resource !== null && $match[0] !== $resource)) {
                return null;
            }

            $resource = $match[0];
            $scopes[] = $match[1];
        }

        return $resource === null ? null : [$resource, BundleEntryScope::merge($scopes)];
    }

    /**
     * Look a reference up among the entries of a Bundle.
     *
     * Returns null when the Bundle has no answer, so the other strategies should run. Otherwise
     * returns what it found, and only a single match is a resolution: an empty or longer list means
     * the Bundle decided the reference and no other strategy should try.
     *
     * @return list<ResolvedResource>|null
     */
    private function resolveInBundle(string $url, string $baseUrl, string $fragment, BundleIndex $bundleIndex, ?BundleEntryScope $scope, EvaluationContext $context): ?array
    {
        // A value with no origin may belong to a nested Bundle, and nothing stops two Bundles using
        // one `fullUrl` for different resources, so a root holding one cannot answer for it.
        if ($scope === null) {
            $this->indexReferences($bundleIndex, $context);
            if ($bundleIndex->containsNestedBundle) {
                return [];
            }
        }

        if ($baseUrl === '') {
            $holders        = $this->referringEntries($bundleIndex, $url, $scope, $context);
            $containedScope = BundleEntryScope::merge($holders);

            $found = [];
            foreach ($holders as $holder) {
                $contained = $this->getContainedResource($this->readProperty($holder->entry, 'resource'), $fragment, $context);

                // Any of these entries could be the one that referred, so one that cannot answer
                // means the reference is unresolved, not that the others win.
                if ($contained === null) {
                    return [];
                }

                if (!in_array($contained, $found, true)) {
                    $found[] = $contained;
                }
            }

            return array_map(static fn (array|object $contained): array => [$contained, $containedScope], $found);
        }

        $target = $baseUrl;
        if (preg_match(self::ABSOLUTE_REFERENCE, $baseUrl) !== 1) {
            if (preg_match(self::RELATIVE_REFERENCE, $baseUrl) !== 1) {
                return null;
            }

            $targets = [];
            foreach ($this->referringEntries($bundleIndex, $url, $scope, $context) as $holder) {
                $targets[] = $this->absoluteTarget($baseUrl, $holder->entry, $context);
            }
            $targets = array_values(array_unique($targets, SORT_REGULAR));

            if (count($targets) > 1) {
                return [];
            }

            $target = $targets[0] ?? null;
            if ($target === null) {
                return null;
            }
        }

        $matches = $this->entriesWithFullUrl($bundleIndex, $target, $context);

        return $matches === [] ? null : $matches;
    }

    /**
     * Make a relative reference absolute against the referring entry's `fullUrl`.
     *
     * @return string|null Absolute URL, or null when the `fullUrl` is absent or not RESTful
     */
    private function absoluteTarget(string $relative, mixed $entry, EvaluationContext $context): ?string
    {
        $fullUrl = $this->readValue($entry, 'fullUrl', $context);
        if (!is_string($fullUrl) || preg_match(self::RESTFUL_FULL_URL, $fullUrl, $matches) !== 1) {
            return null;
        }

        return $matches[1] . $relative;
    }

    /**
     * Find the entries whose `fullUrl` matches, honouring a version in the URL.
     *
     * @return list<ResolvedResource>
     */
    private function entriesWithFullUrl(BundleIndex $bundleIndex, string $target, EvaluationContext $context): array
    {
        $version = null;
        if (preg_match('#^(.+)/_history/([^/]+)$#', $target, $parts) === 1) {
            [, $target, $version] = $parts;
        }

        if ($bundleIndex->entriesByFullUrl === null) {
            $bundleIndex->entriesByFullUrl = [];
            foreach ($this->entries($bundleIndex->bundle) as $position => $entry) {
                $resource = $this->readProperty($entry, 'resource');
                $fullUrl  = $this->readValue($entry, 'fullUrl', $context);
                if ((is_array($resource) || is_object($resource)) && is_string($fullUrl)) {
                    $bundleIndex->entriesByFullUrl[$fullUrl][] = [$resource, $bundleIndex->scopeFor($position, $entry)];
                }
            }
        }

        $matches = [];
        foreach ($bundleIndex->entriesByFullUrl[$target] ?? [] as $match) {
            if ($version === null || $this->readValue($this->readProperty($match[0], 'meta'), 'versionId', $context) === $version) {
                $matches[] = $match;
            }
        }

        return $matches;
    }

    /**
     * Find the entry a reference came from.
     *
     * With no scope, evaluation lost track of it, so the entries whose resource holds a Reference with
     * the given value stand in.
     *
     * @return list<BundleEntryScope>
     */
    private function referringEntries(BundleIndex $bundleIndex, string $reference, ?BundleEntryScope $scope, EvaluationContext $context): array
    {
        if ($scope !== null) {
            return [$scope];
        }

        $this->indexReferences($bundleIndex, $context);

        return $bundleIndex->entriesByReference[$reference] ?? [];
    }

    /**
     * Record which entries hold each Reference value, and whether any of them holds a Bundle.
     */
    private function indexReferences(BundleIndex $bundleIndex, EvaluationContext $context): void
    {
        if ($bundleIndex->entriesByReference !== null) {
            return;
        }

        $bundleIndex->entriesByReference = [];
        foreach ($this->entries($bundleIndex->bundle) as $position => $entry) {
            $held = [];
            $this->collectReferences($this->readProperty($entry, 'resource'), $held, $bundleIndex->containsNestedBundle, $context);
            foreach (array_keys($held) as $value) {
                $bundleIndex->entriesByReference[$value][] = $bundleIndex->scopeFor($position, $entry);
            }
        }
    }

    /**
     * Collect the values of every Reference under a node, as the keys of $held, stopping at a Bundle
     * because what it holds belongs to its own entries.
     *
     * @param array<array-key, true> $held
     */
    private function collectReferences(mixed $node, array &$held, bool &$foundBundle, EvaluationContext $context): void
    {
        if (!is_array($node) && !is_object($node)) {
            return;
        }

        if ($context->getEvaluator()?->matchesResourceType($node, 'Bundle') === true) {
            $foundBundle = true;

            return;
        }

        $reference = $this->readValue($node, 'reference', $context);
        if (is_string($reference)) {
            $held[$reference] = true;
        }

        foreach (is_array($node) ? $node : get_object_vars($node) as $child) {
            $this->collectReferences($child, $held, $foundBundle, $context);
        }
    }

    /**
     * Read `Bundle.entry` as a list, whether or not a lone entry was stored as a list.
     *
     * @return list<mixed>
     */
    private function entries(mixed $bundle): array
    {
        $entries = $this->readProperty($bundle, 'entry');
        if (!is_array($entries)) {
            return [];
        }

        return array_is_list($entries) ? $entries : [$entries];
    }

    /**
     * Read a primitive element as its scalar value, through any FHIR wrapper around it.
     */
    private function readValue(mixed $node, string $property, EvaluationContext $context): mixed
    {
        $value = $this->readProperty($node, $property);
        if (is_array($value) && array_key_exists('@value', $value)) {
            $value = $value['@value'];
        }

        return $context->normalizeValue($value);
    }

    /**
     * Read a named element from an array or an object.
     */
    private function readProperty(mixed $node, string $property): mixed
    {
        if (is_array($node)) {
            return $node[$property] ?? null;
        }

        return is_object($node) ? $this->getObjectProperty($node, $property) : null;
    }

    /**
     * Request a resource by canonical URL from the FHIR server.
     *
     * Mirrors the JS `requestResourceByCanonicalUrl()` function.
     *
     * Builds the query `{fhirServerUrl}/{refType}?url={canonicalUrl}&version={version}`,
     * fetches it (which returns a searchset Bundle), then extracts `entry[0].resource`.
     *
     * @param string                       $url            Canonical URL, optionally with `|version` suffix
     * @param string                       $refType        FHIR resource type (e.g. 'ValueSet')
     * @param string|null                  $fhirServerUrl  Base FHIR server URL
     * @param ClientInterface|null         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface|null $requestFactory PSR-17 request factory
     *
     * @return array<string,mixed>|null
     */
    private function fetchCanonical(string $url, string $refType, ?string $fhirServerUrl, ?ClientInterface $httpClient, ?RequestFactoryInterface $requestFactory): ?array
    {
        if ($fhirServerUrl === null || $httpClient === null) {
            return null;
        }

        // Split "url|version" into components (mirrors JS regex `/^(https?:\/\/[^|]*)(\|(.*))?/`)
        if (preg_match('/^([^|]+)(?:\|(.+))?$/', $url, $matches) !== 1) {
            return null;
        }

        $canonicalUrl = $matches[1];
        $version      = $matches[2] ?? null;

        $params = ['url' => $canonicalUrl];
        if ($version !== null) {
            $params['version'] = $version;
        }

        $searchUrl = $fhirServerUrl . '/' . $refType . '?' . http_build_query($params);
        $bundle    = $this->fetch($searchUrl, $httpClient, $requestFactory);

        // Extract the first entry resource from the searchset Bundle
        if (is_array($bundle) && isset($bundle['entry'][0]['resource']) && is_array($bundle['entry'][0]['resource'])) {
            return $bundle['entry'][0]['resource'];
        }

        return null;
    }

    /**
     * Retrieve a contained resource from a FHIR resource by its fragment ID.
     *
     * Mirrors the JS `getContainedResource()` function.
     *
     * @param mixed             $resource       FHIR resource that may have a `contained` array
     * @param string            $containedResId The `id` value to search for
     * @param EvaluationContext $context        Evaluation context (value normalisation)
     *
     * @return array<string,mixed>|object|null
     */
    private function getContainedResource(mixed $resource, string $containedResId, EvaluationContext $context): array|object|null
    {
        if ($containedResId === '') {
            return null;
        }

        $contained = null;
        if (is_array($resource) && isset($resource['contained']) && is_array($resource['contained'])) {
            $contained = $resource['contained'];
        } elseif (is_object($resource)) {
            $val       = $this->getObjectProperty($resource, 'contained');
            $contained = is_array($val) ? $val : null;
        }

        if ($contained === null) {
            return null;
        }

        foreach ($contained as $item) {
            if (is_array($item) && ($item['id'] ?? null) === $containedResId) {
                return $item;
            }

            if (is_object($item) && $context->normalizeValue($this->getObjectProperty($item, 'id')) === $containedResId) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Send a GET request via the PSR-18 client and decode the JSON response body.
     *
     * Returns null on any transport error, non-2xx response, or non-JSON body.
     *
     * @param ClientInterface|null         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface|null $requestFactory PSR-17 request factory
     *
     * @return array<string,mixed>|null
     */
    private function fetch(string $url, ?ClientInterface $httpClient, ?RequestFactoryInterface $requestFactory): ?array
    {
        if ($httpClient === null || $requestFactory === null) {
            return null;
        }

        try {
            $request  = $requestFactory->createRequest('GET', $url);
            $response = $httpClient->sendRequest($request);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                return null;
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            return is_array($data) ? $data : null;
        } catch (ClientExceptionInterface) {
            return null;
        }
    }

    /**
     * Get a named property from an object using public property access or a getter.
     */
    private function getObjectProperty(object $obj, string $property): mixed
    {
        $vars = get_object_vars($obj);
        if (array_key_exists($property, $vars)) {
            return $vars[$property];
        }

        $getter = 'get' . ucfirst($property);
        if (method_exists($obj, $getter)) {
            return $obj->$getter();
        }

        return null;
    }
}

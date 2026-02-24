<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

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
 *
 * Resolution strategies (in order, mirroring the reference JavaScript engine):
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
 * @author FHIR Tools Contributors
 */
final class ResolveFunction extends AbstractFunction
{
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
        $rootResource   = $context->getRootResource();

        $resolved = [];
        foreach ($input as $item) {
            $resource = $this->resolveItem($item, $rootResource, $fhirServerUrl, $httpClient, $requestFactory);
            if ($resource !== null && isset($resource['resourceType'])) {
                $resolved[] = $resource;
            }
        }

        return Collection::from($resolved);
    }

    /**
     * Resolve a single input item to a FHIR resource.
     *
     * Mirrors the JS `engine.resolveFn` dispatch logic:
     *  - FHIR.Reference (array/object with `reference` key) → use reference URL
     *  - String (uri, canonical, FHIR.string) → use as URL directly
     *
     * @param mixed                        $rootResource   Root resource of the evaluation (for contained lookup)
     * @param string|null                  $fhirServerUrl  Base FHIR server URL (may be null)
     * @param ClientInterface|null         $httpClient     PSR-18 HTTP client (may be null)
     * @param RequestFactoryInterface|null $requestFactory PSR-17 request factory (may be null)
     *
     * @return array<string,mixed>|null Decoded FHIR resource array, or null if unresolvable
     */
    private function resolveItem(mixed $item, mixed $rootResource, ?string $fhirServerUrl, ?ClientInterface $httpClient, ?RequestFactoryInterface $requestFactory): ?array
    {
        // FHIR.Reference: array or object with a 'reference' property
        if (is_array($item) && isset($item['reference']) && is_string($item['reference'])) {
            $refType = isset($item['type']) && is_string($item['type']) ? $item['type'] : null;

            return $this->resolveUrl($item['reference'], false, $refType, $rootResource, $fhirServerUrl, $httpClient, $requestFactory);
        }

        if (is_object($item)) {
            $ref = $this->getObjectProperty($item, 'reference');
            if (is_string($ref)) {
                $refType = $this->getObjectProperty($item, 'type');
                $refType = is_string($refType) ? $refType : null;

                return $this->resolveUrl($ref, false, $refType, $rootResource, $fhirServerUrl, $httpClient, $requestFactory);
            }
        }

        // Plain string: uri, canonical, or System.String
        if (is_string($item)) {
            return $this->resolveUrl($item, false, null, $rootResource, $fhirServerUrl, $httpClient, $requestFactory);
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
     * @param mixed                        $rootResource   Root resource for contained resolution
     * @param string|null                  $fhirServerUrl  Base FHIR server URL
     * @param ClientInterface|null         $httpClient     PSR-18 HTTP client
     * @param RequestFactoryInterface|null $requestFactory PSR-17 request factory
     *
     * @return array<string,mixed>|null
     */
    private function resolveUrl(string $url, bool $isCanonical, ?string $refType, mixed $rootResource, ?string $fhirServerUrl, ?ClientInterface $httpClient, ?RequestFactoryInterface $requestFactory): ?array
    {
        // Split on first '#' to separate base URL from fragment (contained resource ID)
        $hashPos  = strpos($url, '#');
        $baseUrl  = $hashPos !== false ? substr($url, 0, $hashPos) : $url;
        $fragment = $hashPos !== false ? substr($url, $hashPos + 1) : '';

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
            return $this->getContainedResource($rootResource, $fragment);
        }

        if ($resource !== null && $fragment !== '') {
            // URL + fragment → fetch resource, then find contained by ID
            return $this->getContainedResource($resource, $fragment);
        }

        return $resource;
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
     * @param mixed  $resource       FHIR resource that may have a `contained` array
     * @param string $containedResId The `id` value to search for
     *
     * @return array<string,mixed>|null
     */
    private function getContainedResource(mixed $resource, string $containedResId): ?array
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

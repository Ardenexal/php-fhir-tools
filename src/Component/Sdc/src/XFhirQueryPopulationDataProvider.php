<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Ardenexal\FHIRTools\Component\Sdc\Contract\QueryPopulationDataProviderInterface;
use Ardenexal\FHIRTools\Component\HttpClient\NullFHIRHttpClient;

/**
 * Live-fetch provider for `application/x-fhir-query`-driven population.
 *
 * Executes an already-resolved FHIR search against a FHIR server (via the injected
 * {@see FHIRHttpClientInterface}) and returns the resources in the result Bundle. This is the network seam:
 * supply it on a {@see PopulateContext} to opt into live x-fhir-query support; omit it (or wire a
 * {@see NullFHIRHttpClient}) to keep population offline-first.
 */
final class XFhirQueryPopulationDataProvider implements QueryPopulationDataProviderInterface
{
    /**
     * Hard bound on pages followed per search. `FHIRHttpClientInterface::followLink()` already rejects a
     * cross-origin `next` link (the SSRF guardrail); this bounds iteration count instead — a distinct
     * concern, since a same-origin but misbehaving (or malicious) server could otherwise serve an endless
     * chain of `next` links. Reaching the bound simply stops pagination; whatever pages were fetched
     * successfully so far are still returned (see resourcesForQuery()).
     */
    private const MAX_PAGES = 50;

    public function __construct(
        private readonly FHIRHttpClientInterface $client,
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
    ) {
    }

    public function resourcesForQuery(string $resolvedSearch, string $fhirVersion): ?array
    {
        $bundle = $this->client->search($resolvedSearch, $fhirVersion);
        if ($bundle === null) {
            return null; // fetch failure — distinct from an empty searchset
        }

        $resources = $this->matchingResources($bundle, $fhirVersion);

        // Follow Bundle.link[relation=next] across pages, bounded by MAX_PAGES. A page fetch that yields no
        // further page — whether because there is no `next` link, the link was rejected as cross-origin, or
        // the fetch failed (transport/HTTP/parse error) — simply stops pagination here, mirroring the
        // graceful-degradation posture used throughout this transport stack: whatever pages were fetched
        // successfully are returned, with no distinct partial-failure signal from later pages.
        for ($page = 1; $page < self::MAX_PAGES; ++$page) {
            $nextUrl = $this->nextLinkUrl($bundle, $fhirVersion);
            if ($nextUrl === null) {
                break;
            }

            $next = $this->client->followLink($nextUrl, $fhirVersion);
            if ($next === null) {
                break;
            }

            $bundle    = $next;
            $resources = [...$resources, ...$this->matchingResources($bundle, $fhirVersion)];
        }

        return $resources;
    }

    /**
     * Navigate `entry.where(search.mode = 'match').resource` via the FHIRPath engine: it reads
     * deserializer-origin objects tolerantly (getters, uninitialized typed properties, arrays). Filtering
     * to `search.mode = 'match'` excludes `_include`d resources and `search.mode = 'outcome'`
     * (OperationOutcome) entries, which would otherwise be bound as spurious %<name> context results.
     * An entry with no `search.mode` (a plain, non-searchset Bundle) is not a match and is excluded.
     *
     * @return list<object>
     */
    private function matchingResources(object $bundle, string $fhirVersion): array
    {
        $resources = [];
        foreach ($this->fhirPath->evaluate("entry.where(search.mode = 'match').resource", $bundle, null, $fhirVersion)->toArray() as $item) {
            if (\is_object($item)) {
                $resources[] = $item;
            }
        }

        return $resources;
    }

    /**
     * Extract `Bundle.link.where(relation = 'next').url` via the FHIRPath engine, which already normalizes
     * FHIR primitive wrappers to plain scalars — this reads uniformly across FHIR versions despite `relation`
     * being a plain string in R4/R4B and a code-typed enum wrapper in R5.
     */
    private function nextLinkUrl(object $bundle, string $fhirVersion): ?string
    {
        $url = $this->fhirPath->evaluate("link.where(relation = 'next').url", $bundle, null, $fhirVersion)->first();

        return \is_string($url) && $url !== '' ? $url : null;
    }
}

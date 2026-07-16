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

        // Navigate `entry.resource` via the FHIRPath engine: it reads deserializer-origin objects tolerantly
        // (getters, uninitialized typed properties, arrays) and returns the entry resources. Taking every
        // entry resource (not just search.mode = 'match') is a documented M04 refinement.
        $resources = [];
        foreach ($this->fhirPath->evaluate('entry.resource', $bundle, null, $fhirVersion)->toArray() as $item) {
            if (\is_object($item)) {
                $resources[] = $item;
            }
        }

        return $resources;
    }
}

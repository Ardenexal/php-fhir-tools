<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Contract;

use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Sdc\XFhirQueryPopulationDataProvider;

/**
 * Optional live-fetch seam for `application/x-fhir-query`-driven population.
 *
 * Population is offline-first: without a provider of this kind, x-fhir-query context expressions are
 * skipped with a warning (see {@see PopulationDataProviderInterface} for the observation seam). When a
 * caller wants live x-fhir-query support, it supplies an implementation (e.g.
 * {@see XFhirQueryPopulationDataProvider}) on the
 * {@see PopulateContext}.
 *
 * The populate service resolves the x-fhir-query *template* (offline, via FHIRPath) before calling this
 * seam — implementations receive an already-resolved FHIR search string and only execute it. This keeps
 * template resolution offline and confines the network boundary to the provider.
 */
interface QueryPopulationDataProviderInterface
{
    /**
     * Execute a resolved FHIR search and return the matching resources.
     *
     * @param string $resolvedSearch a concrete FHIR search string, e.g. `Observation?subject=Patient/123`
     * @param string $fhirVersion    the model namespace to deserialize into: `R4`, `R4B`, or `R5`
     *
     * @return list<object>|null the resources from the result Bundle; an empty list means the search matched
     *                           nothing, whereas `null` means the fetch itself failed (the caller distinguishes
     *                           these — a failure is a warning, an empty match is an informational note)
     */
    public function resourcesForQuery(string $resolvedSearch, string $fhirVersion): ?array;
}

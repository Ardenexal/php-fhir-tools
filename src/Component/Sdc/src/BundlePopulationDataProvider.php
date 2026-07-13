<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

/**
 * In-memory {@see PopulationDataProviderInterface} backed by a pre-fetched FHIR `Bundle`.
 *
 * The caller supplies a `Bundle` (a `searchset`/`collection` of the resources relevant to population);
 * this provider surfaces its `Observation` entries. Reads are tolerant of deserializer-origin objects
 * (uninitialized typed properties read via `isset`), so a Bundle straight from the serializer is safe.
 */
final class BundlePopulationDataProvider implements PopulationDataProviderInterface
{
    public function __construct(
        private readonly object $bundle,
    ) {
    }

    public function observations(): array
    {
        $entries = $this->bundle->entry ?? null;
        if (!\is_array($entries)) {
            return [];
        }

        $observations = [];
        foreach ($entries as $entry) {
            if (!\is_object($entry)) {
                continue;
            }

            $resource = $entry->resource ?? null;
            if (\is_object($resource) && $this->isObservation($resource)) {
                $observations[] = $resource;
            }
        }

        return $observations;
    }

    /**
     * Whether a resource object is an `Observation`, by class basename (version-agnostic — matches
     * `Models\R4\...\ObservationResource`, R4B, R5) rather than a hardcoded FQCN.
     */
    private function isObservation(object $resource): bool
    {
        $class = $resource::class;
        $short = ($pos = strrpos($class, '\\')) !== false ? substr($class, $pos + 1) : $class;

        return $short === 'ObservationResource' || $short === 'Observation';
    }
}

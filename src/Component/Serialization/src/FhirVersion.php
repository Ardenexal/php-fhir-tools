<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * Supported FHIR major versions.
 *
 * Used to scope normalizer stacks, type registries, and type resolver instances
 * to a specific version so that cross-version class collisions are impossible and
 * the correct Extension base class is resolved without runtime class_exists probing.
 *
 * The string value maps directly onto the namespace segment used in generated model
 * classes (e.g. FhirVersion::R4->value === 'R4' → Models\R4\DataType\Extension).
 */
enum FhirVersion: string
{
    case R4  = 'R4';
    case R4B = 'R4B';
    case R5  = 'R5';

    /**
     * Return the FQCN of the base Extension DataType class for this version.
     *
     * Used by normalizers as the fallback class when no typed IG extension is
     * registered for a given URL.
     */
    public function extensionFqcn(): string
    {
        return "Ardenexal\\FHIRTools\\Component\\Models\\{$this->value}\\DataType\\Extension";
    }
}

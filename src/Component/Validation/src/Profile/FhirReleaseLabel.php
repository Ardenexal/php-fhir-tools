<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Maps a StructureDefinition's numeric `fhirVersion` to the release label the models use.
 *
 * Validation keys everything by 'R4', 'R4B' or 'R5', the same labels as the model namespaces,
 * while StructureDefinitions and package manifests carry the spec version ('4.0.1', '4.3.0',
 * '5.0.0'). The canonical URL alone is not a key: core profiles such as
 * `http://hl7.org/fhir/StructureDefinition/bp` exist in several releases.
 *
 * @internal
 *
 * @author Ardenexal
 */
final class FhirReleaseLabel
{
    /**
     * Normalise a spec version or release label to 'R4', 'R4B' or 'R5'.
     *
     * @param string $version A spec version such as '4.0.1' or a label such as 'r4b'
     *
     * @return string|null The release label, or null for a version this toolkit has no models for
     */
    public static function normalize(string $version): ?string
    {
        $upper = strtoupper(trim($version));

        return match (true) {
            in_array($upper, ['R4', 'R4B', 'R5'], true) => $upper,
            str_starts_with($upper, '4.0')              => 'R4',
            str_starts_with($upper, '4.3')              => 'R4B',
            str_starts_with($upper, '5.0')              => 'R5',
            default                                     => null,
        };
    }
}

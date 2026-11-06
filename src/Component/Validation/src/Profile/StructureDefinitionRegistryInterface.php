<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Source of StructureDefinitions that runtime profile validation can look up by canonical URL.
 *
 * Every lookup names the FHIR release ('R4', 'R4B' or 'R5') because one canonical URL can exist
 * in several releases with different rules. Implementations return the decoded JSON as an array;
 * they do not check that a snapshot is present, which is the resolver's job.
 *
 * @author Ardenexal
 */
interface StructureDefinitionRegistryInterface
{
    /**
     * Find a StructureDefinition by canonical URL.
     *
     * @param string      $fhirVersion 'R4', 'R4B' or 'R5'
     * @param string      $url         Canonical URL without a `|version` suffix
     * @param string|null $version     Business version to match exactly; null returns the latest
     *
     * @return array<string, mixed>|null The decoded StructureDefinition, or null when none matches
     */
    public function find(string $fhirVersion, string $url, ?string $version = null): ?array;

    /**
     * List the FHIR releases that hold a StructureDefinition with this canonical URL.
     *
     * Lets a caller tell "this profile does not exist" apart from "this profile exists, but
     * for another FHIR release than the resource being validated".
     *
     * @param string $url Canonical URL without a `|version` suffix
     *
     * @return list<string> Release labels, e.g. ['R4B', 'R5']; empty when the URL is unknown
     */
    public function fhirVersionsFor(string $url): array;
}

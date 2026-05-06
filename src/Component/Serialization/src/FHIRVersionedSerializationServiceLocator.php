<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * Provides access to per-version FHIRSerializationService instances.
 *
 * Registered as a public service by FHIRVersionedSerializerPass. Inject this
 * class when the FHIR version is determined at runtime (e.g. from a request
 * parameter or a resource's meta.fhirVersion field).
 *
 * For code that always targets a single known version, inject the named service
 * alias directly (e.g. `fhir.serialization_service.r4`) or rely on the
 * `fhir.serialization_service` alias which resolves to the configured default.
 *
 * @author Ardenexal
 */
final class FHIRVersionedSerializationServiceLocator
{
    public function __construct(
        private readonly FHIRSerializationService $r4,
        private readonly FHIRSerializationService $r4b,
        private readonly FHIRSerializationService $r5,
    ) {
    }

    /**
     * Return the serialization service for the given FHIR version.
     */
    public function get(FhirVersion $version): FHIRSerializationService
    {
        return match ($version) {
            FhirVersion::R4  => $this->r4,
            FhirVersion::R4B => $this->r4b,
            FhirVersion::R5  => $this->r5,
        };
    }

    /**
     * Return the serialization service for a FHIR version string (e.g. 'R4', 'R4B', 'R5').
     *
     * Throws \ValueError when an unrecognised version string is supplied.
     */
    public function getForVersion(string $version): FHIRSerializationService
    {
        return $this->get(FhirVersion::from($version));
    }
}

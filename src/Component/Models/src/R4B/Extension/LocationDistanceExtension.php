<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/location-distance
 *
 * @description A calculated distance between the resource and a provided location.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/location-distance', fhirVersion: 'R4B')]
class LocationDistanceExtension extends Extension
{
    public function __construct(
        /** @var Distance|null valueDistance Value of extension */
        #[FhirProperty(fhirType: 'Distance', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Distance $valueDistance = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/location-distance',
            value: $this->valueDistance,
        );
    }
}

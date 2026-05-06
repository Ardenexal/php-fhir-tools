<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Distance;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/location-distance
 *
 * @description This extension is only applicable to searchset Bundles. It is the result of a search on Location using the "near" parameter indicating the calculated distance between the geographic location of this Location resource and the geographic location provided in the search query.
 *
 * This distance does not consider a location boundary extension if it exists on the resource.
 *
 * If multiple near positions are included in the search query, the distance to the closest point provided may be included.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/location-distance', fhirVersion: 'R4B')]
class BundleLocationDistanceExtension extends Extension
{
    public function __construct(
        /** @var Distance|null valueDistance Value of extension */
        #[FhirProperty(fhirType: 'Distance', propertyKind: 'complex')]
        public ?Distance $valueDistance = null,
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

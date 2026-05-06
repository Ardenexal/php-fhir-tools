<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/location-boundary-geojson
 *
 * @description A boundary shape that represents the outside edge of the location (in GeoJSON format) This shape may have holes, and disconnected shapes.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/location-boundary-geojson', fhirVersion: 'R4')]
class LocBoundaryGeojsonExtension extends Extension
{
    public function __construct(
        /** @var Attachment|null valueAttachment Value of extension */
        #[FhirProperty(fhirType: 'Attachment', propertyKind: 'complex')]
        public ?Attachment $valueAttachment = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/location-boundary-geojson',
            value: $this->valueAttachment,
        );
    }
}

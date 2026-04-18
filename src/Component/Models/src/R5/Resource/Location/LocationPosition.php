<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Location;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The absolute geographic location of the Location, expressed using the WGS84 datum (This is the same co-ordinate system used in KML).
 */
#[FHIRBackboneElement(parentResource: 'Location', elementPath: 'Location.position', fhirVersion: 'R5')]
class LocationPosition extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var numeric-string|null longitude Longitude with WGS84 datum */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?string $longitude = null,
        /** @var numeric-string|null latitude Latitude with WGS84 datum */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?string $latitude = null,
        /** @var numeric-string|null altitude Altitude with WGS84 datum */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $altitude = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

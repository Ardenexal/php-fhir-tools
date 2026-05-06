<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Availability
 *
 * @description Availability data for an {item}.
 */
#[FHIRComplexType(typeName: 'Availability', fhirVersion: 'R5')]
class Availability extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<AvailabilityAvailableTime> availableTime Times the {item} is available */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\AvailabilityAvailableTime',
        )]
        public array $availableTime = [],
        /** @var array<AvailabilityNotAvailableTime> notAvailableTime Not available during this time due to provided reason */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\AvailabilityNotAvailableTime',
        )]
        public array $notAvailableTime = [],
    ) {
        parent::__construct($id, $extension);
    }
}

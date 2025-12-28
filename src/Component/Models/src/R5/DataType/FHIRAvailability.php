<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAvailabilityAvailableTime;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAvailabilityNotAvailableTime;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Availability
 *
 * @description Availability data for an {item}.
 */
#[FHIRComplexType(typeName: 'Availability', fhirVersion: 'R5')]
class FHIRAvailability extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRAvailabilityAvailableTime> availableTime Times the {item} is available */
        public array $availableTime = [],
        /** @var array<FHIRAvailabilityNotAvailableTime> notAvailableTime Not available during this time due to provided reason */
        public array $notAvailableTime = [],
    ) {
        parent::__construct($id, $extension);
    }
}

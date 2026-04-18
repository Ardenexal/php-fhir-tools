<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Pharmacy
 *
 * @see http://hl7.org/fhir/StructureDefinition/medicationdispense-refillsRemaining
 *
 * @description The number of refills allowed or remaining after a dispensing event.  Does not include the current dispense.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/medicationdispense-refillsRemaining', fhirVersion: 'R4')]
class MedRefillsRemainingExtension extends Extension
{
    public function __construct(
        /** @var int|null valueInteger Value of extension */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $valueInteger = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/medicationdispense-refillsRemaining',
            value: $this->valueInteger,
        );
    }
}

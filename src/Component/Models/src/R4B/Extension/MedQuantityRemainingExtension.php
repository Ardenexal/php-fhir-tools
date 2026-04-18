<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;

/**
 * @author HL7 International / Pharmacy
 *
 * @see http://hl7.org/fhir/StructureDefinition/medicationdispense-quantityRemaining
 *
 * @description The quanity left to be dispensed after a dispensing event.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/medicationdispense-quantityRemaining', fhirVersion: 'R4B')]
class MedQuantityRemainingExtension extends Extension
{
    public function __construct(
        /** @var Quantity|null valueQuantity Value of extension */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $valueQuantity = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/medicationdispense-quantityRemaining',
            value: $this->valueQuantity,
        );
    }
}

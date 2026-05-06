<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @author HL7 International / Pharmacy
 *
 * @see http://hl7.org/fhir/StructureDefinition/medication-manufacturer
 *
 * @description This extension indicates The manufacturer of the medication.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/medication-manufacturer', fhirVersion: 'R4')]
class MedicationManufacturerExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/medication-manufacturer',
            value: $this->valueReference,
        );
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-birthPlace
 *
 * @description The registered place of birth of the patient. A sytem may use the address.text if they don't store the birthPlace address in discrete elements.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-birthPlace', fhirVersion: 'R4B')]
class PatBirthPlaceExtension extends Extension
{
    public function __construct(
        /** @var Address|null valueAddress Value of extension */
        #[FhirProperty(fhirType: 'Address', propertyKind: 'complex')]
        public ?Address $valueAddress = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-birthPlace',
            value: $this->valueAddress,
        );
    }
}

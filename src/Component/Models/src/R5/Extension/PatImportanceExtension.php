<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-importance
 *
 * @description The importance of the patient (e.g. VIP).
 *
 * When considering using this extension you should also consider if using any or all of the following is also appropriate:
 * `Encounter.specialCourtesy` which provides a simple flag indicating additional `benefits` that the patient might be entitled to during a visit *(e.g. special room types)*.
 * `Resource.meta.security` with codes such as `VIP`. These might be used by actual data level security controls within the system, potentially requiring specific user permissions to access the data.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-importance', fhirVersion: 'R5')]
class PatImportanceExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-importance',
            value: $this->valueCodeableConcept,
        );
    }
}

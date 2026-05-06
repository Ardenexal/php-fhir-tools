<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-fetalStatus
 *
 * @description An indicator on the status of a fetal subject. Typically used in maternity where the patient record may be created before birth. This extension would not apply to Patients that have a birthDate.  A Patient.deceased flag cannot be used for fetal subjects because the fetus is not yet born. The status is used to express the vitality of the fetal subject.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-fetalStatus', fhirVersion: 'R5')]
class PatFetalStatusExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-fetalStatus',
            value: $this->valueCode,
        );
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-multipleBirthTotal
 *
 * @description Where a patient is a part of a multiple birth, this is the total number of births that occurred in this pregnancy. This includes all live births and all fetal losses.
 *
 * When the patients have not been born yet, this is the total number of fetuses that are known to be present.
 *
 * This value is the 4 in '3 of *4*', and the 3 would be the Patient.multipleBirth value.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-multipleBirthTotal', fhirVersion: 'R5')]
class PatMultipleBirthTotalExtension extends Extension
{
    public function __construct(
        /** @var PositiveIntPrimitive|null valuePositiveInt Value of extension */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $valuePositiveInt = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-multipleBirthTotal',
            value: $this->valuePositiveInt,
        );
    }
}

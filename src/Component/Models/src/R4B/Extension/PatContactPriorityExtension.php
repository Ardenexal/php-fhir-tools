<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-contactPriority
 *
 * @description The order in which contacts for a patient should be used.  For example, if a patient has multiple people listed as an emergency contact, which of those should be called first in an emergency.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-contactPriority', fhirVersion: 'R4B')]
class PatContactPriorityExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/patient-contactPriority',
            value: $this->valuePositiveInt,
        );
    }
}

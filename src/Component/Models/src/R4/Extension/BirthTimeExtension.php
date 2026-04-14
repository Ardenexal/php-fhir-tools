<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-birthTime
 *
 * @description The time of day that the Patient was born. This includes the date to ensure that the timezone information can be communicated effectively.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-birthTime', fhirVersion: 'R4')]
class BirthTimeExtension extends Extension
{
    public function __construct(
        /** @var DateTimePrimitive|null valueDateTime Value of extension */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $valueDateTime = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-birthTime',
            value: $this->valueDateTime,
        );
    }
}

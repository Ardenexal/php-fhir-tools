<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-birthTime
 *
 * @description The time of day that the Patient/Person/RelatedPerson/Practitioner was born. This includes the date to ensure that the timezone information can be communicated effectively.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-birthTime', fhirVersion: 'R4B')]
class PatBirthTimeExtension extends Extension
{
    public function __construct(
        /** @var DateTimePrimitive|null valueDateTime Value of extension */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $valueDateTime = null,
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

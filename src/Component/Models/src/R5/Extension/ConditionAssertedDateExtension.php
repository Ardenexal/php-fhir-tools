<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/condition-assertedDate
 *
 * @description When the asserter identified the allergy, intolerance, condition, problem, or diagnosis or other event, situation, issue, or clinical concept that may have risen to a level of or remains a concern.  For example, when the patient experiences chest pain, the asserted date represents when the clinician began following the chest pain - not when the patient experienced the chest pain.  Asserted date supports the recognition that information is not always entered in a system immediately.  Assertion and recording are different acts, so asserted date and recorded date are semantically different. However, they may be the same date and close in time.  If this difference is significant for your use case, assertion date may be useful.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/condition-assertedDate', fhirVersion: 'R5')]
class ConditionAssertedDateExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/condition-assertedDate',
            value: $this->valueDateTime,
        );
    }
}

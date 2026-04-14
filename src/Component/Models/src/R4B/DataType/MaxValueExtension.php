<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/maxValue
 *
 * @description The inclusive upper bound on the range of allowed values for the data element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/maxValue', fhirVersion: 'R4B')]
class MaxValueExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|DateTimePrimitive|TimePrimitive|InstantPrimitive|string|int|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\InstantPrimitive|string|int|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/maxValue',
            value: $this->value,
        );
    }
}

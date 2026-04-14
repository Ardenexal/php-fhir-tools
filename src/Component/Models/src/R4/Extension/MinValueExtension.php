<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/minValue
 *
 * @description The inclusive lower bound on the range of allowed values for the data element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/minValue', fhirVersion: 'R4')]
class MinValueExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|DateTimePrimitive|TimePrimitive|string|int|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive|string|int|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/minValue',
            value: $this->value,
        );
    }
}

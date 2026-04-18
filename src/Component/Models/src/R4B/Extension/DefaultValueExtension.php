<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-defaultValue
 *
 * @description Provides a default value for a parameter definition.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-defaultValue', fhirVersion: 'R4B')]
class DefaultValueExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|bool|int|string|DatePrimitive|DateTimePrimitive|Coding|CodeableConcept|Period|Range|Quantity|Ratio|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        StringPrimitive|bool|int|string|DatePrimitive|DateTimePrimitive|Coding|CodeableConcept|Period|Range|Quantity|Ratio|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-defaultValue',
            value: $value,
        );
    }
}

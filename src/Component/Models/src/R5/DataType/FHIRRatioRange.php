<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/RatioRange
 *
 * @description A range of ratios expressed as a low and high numerator and a denominator.
 */
#[FHIRComplexType(typeName: 'RatioRange', fhirVersion: 'R5')]
class FHIRRatioRange extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRQuantity|null lowNumerator Low Numerator limit */
        public ?\FHIRQuantity $lowNumerator = null,
        /** @var FHIRQuantity|null highNumerator High Numerator limit */
        public ?\FHIRQuantity $highNumerator = null,
        /** @var FHIRQuantity|null denominator Denominator value */
        public ?\FHIRQuantity $denominator = null,
    ) {
        parent::__construct($id, $extension);
    }
}

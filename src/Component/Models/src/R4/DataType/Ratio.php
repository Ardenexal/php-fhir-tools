<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Ratio
 *
 * @description A relationship of two Quantity values - expressed as a numerator and a denominator.
 */
#[FHIRComplexType(typeName: 'Ratio', fhirVersion: 'R4')]
class Ratio extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var Quantity|null numerator Numerator value */
        public ?Quantity $numerator = null,
        /** @var Quantity|null denominator Denominator value */
        public ?Quantity $denominator = null,
    ) {
        parent::__construct($id, $extension);
    }
}

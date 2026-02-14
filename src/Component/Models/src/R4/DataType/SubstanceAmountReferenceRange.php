<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @description Reference range of possible or expected values.
 */
#[FHIRComplexType(typeName: 'SubstanceAmount.referenceRange', fhirVersion: 'R4')]
class SubstanceAmountReferenceRange extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var Quantity|null lowLimit Lower limit possible or expected */
        public ?Quantity $lowLimit = null,
        /** @var Quantity|null highLimit Upper limit possible or expected */
        public ?Quantity $highLimit = null,
    ) {
        parent::__construct($id, $extension);
    }
}

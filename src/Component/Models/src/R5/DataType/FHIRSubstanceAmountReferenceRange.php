<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement;

/**
 * @description Reference range of possible or expected values.
 */
#[FHIRComplexType(typeName: 'SubstanceAmount.referenceRange', fhirVersion: 'R5')]
class FHIRSubstanceAmountReferenceRange extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRQuantity|null lowLimit Lower limit possible or expected */
        public ?FHIRQuantity $lowLimit = null,
        /** @var FHIRQuantity|null highLimit Upper limit possible or expected */
        public ?FHIRQuantity $highLimit = null,
    ) {
        parent::__construct($id, $extension);
    }
}

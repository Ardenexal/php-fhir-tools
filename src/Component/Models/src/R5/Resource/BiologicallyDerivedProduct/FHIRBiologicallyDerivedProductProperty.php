<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A property that is specific to this BiologicallyDerviedProduct instance.
 */
#[FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.property', fhirVersion: 'R5')]
class FHIRBiologicallyDerivedProductProperty extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Code that specifies the property */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRBoolean|FHIRInteger|FHIRCodeableConcept|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRString|string|FHIRAttachment|null valueX Property values */
        #[NotBlank]
        public \FHIRBoolean|\FHIRInteger|\FHIRCodeableConcept|\FHIRPeriod|\FHIRQuantity|\FHIRRange|\FHIRRatio|\FHIRString|string|\FHIRAttachment|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

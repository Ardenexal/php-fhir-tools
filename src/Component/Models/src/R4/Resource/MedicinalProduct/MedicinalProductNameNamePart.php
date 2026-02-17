<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Coding words or phrases of the name.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.name.namePart', fhirVersion: 'R4')]
class MedicinalProductNameNamePart extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null part A fragment of a product name */
        #[NotBlank]
        public StringPrimitive|string|null $part = null,
        /** @var Coding|null type Idenifying type for this part of the name (e.g. strength part) */
        #[NotBlank]
        public ?Coding $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

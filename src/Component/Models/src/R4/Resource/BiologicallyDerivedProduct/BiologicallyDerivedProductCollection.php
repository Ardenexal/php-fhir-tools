<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @description How this product was collected.
 */
#[FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.collection', fhirVersion: 'R4')]
class BiologicallyDerivedProductCollection extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null collector Individual performing collection */
        public ?Reference $collector = null,
        /** @var Reference|null source Who is product from */
        public ?Reference $source = null,
        /** @var DateTimePrimitive|Period|null collectedX Time of product collection */
        public DateTimePrimitive|Period|null $collectedX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

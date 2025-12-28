<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;

/**
 * @description How this product was collected.
 */
#[FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.collection', fhirVersion: 'R4B')]
class FHIRBiologicallyDerivedProductCollection extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null collector Individual performing collection */
        public ?FHIRReference $collector = null,
        /** @var FHIRReference|null source Who is product from */
        public ?FHIRReference $source = null,
        /** @var FHIRDateTime|FHIRPeriod|null collectedX Time of product collection */
        public FHIRDateTime|FHIRPeriod|null $collectedX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

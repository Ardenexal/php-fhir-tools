<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BiologicallyDerivedProductStorageScaleType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Product storage.
 */
#[FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.storage', fhirVersion: 'R4')]
class BiologicallyDerivedProductStorage extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null description Description of storage */
        public StringPrimitive|string|null $description = null,
        /** @var float|null temperature Storage temperature */
        public ?float $temperature = null,
        /** @var BiologicallyDerivedProductStorageScaleType|null scale farenheit | celsius | kelvin */
        public ?BiologicallyDerivedProductStorageScaleType $scale = null,
        /** @var Period|null duration Storage timeperiod */
        public ?Period $duration = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

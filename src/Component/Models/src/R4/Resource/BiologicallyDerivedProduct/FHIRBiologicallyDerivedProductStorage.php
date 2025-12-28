<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Product storage.
 */
#[FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.storage', fhirVersion: 'R4')]
class FHIRBiologicallyDerivedProductStorage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Description of storage */
        public FHIRString|string|null $description = null,
        /** @var FHIRDecimal|null temperature Storage temperature */
        public ?FHIRDecimal $temperature = null,
        /** @var FHIRBiologicallyDerivedProductStorageScaleType|null scale farenheit | celsius | kelvin */
        public ?FHIRBiologicallyDerivedProductStorageScaleType $scale = null,
        /** @var FHIRPeriod|null duration Storage timeperiod */
        public ?FHIRPeriod $duration = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;

/**
 * @description The starting materials - monomer(s) used in the synthesis of the polymer.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.monomerSet.startingMaterial', fhirVersion: 'R5')]
class FHIRSubstancePolymerMonomerSetStartingMaterial extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code The type of substance for this starting material */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null category Substance high level category, e.g. chemical substance */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRBoolean|null isDefining Used to specify whether the attribute described is a defining element for the unique identification of the polymer */
        public ?FHIRBoolean $isDefining = null,
        /** @var FHIRQuantity|null amount A percentage */
        public ?FHIRQuantity $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

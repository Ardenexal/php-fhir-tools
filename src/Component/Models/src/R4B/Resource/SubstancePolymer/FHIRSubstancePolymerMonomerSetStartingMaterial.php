<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.monomerSet.startingMaterial', fhirVersion: 'R4B')]
class FHIRSubstancePolymerMonomerSetStartingMaterial extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null material Todo */
        public ?FHIRCodeableConcept $material = null,
        /** @var FHIRCodeableConcept|null type Todo */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRBoolean|null isDefining Todo */
        public ?FHIRBoolean $isDefining = null,
        /** @var FHIRSubstanceAmount|null amount Todo */
        public ?FHIRSubstanceAmount $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

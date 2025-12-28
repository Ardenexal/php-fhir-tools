<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.monomerSet', fhirVersion: 'R4B')]
class FHIRSubstancePolymerMonomerSet extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null ratioType Todo */
        public ?\FHIRCodeableConcept $ratioType = null,
        /** @var array<FHIRSubstancePolymerMonomerSetStartingMaterial> startingMaterial Todo */
        public array $startingMaterial = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

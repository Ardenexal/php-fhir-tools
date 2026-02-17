<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SubstanceAmount;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.monomerSet.startingMaterial', fhirVersion: 'R4')]
class SubstancePolymerMonomerSetStartingMaterial extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null material Todo */
        public ?CodeableConcept $material = null,
        /** @var CodeableConcept|null type Todo */
        public ?CodeableConcept $type = null,
        /** @var bool|null isDefining Todo */
        public ?bool $isDefining = null,
        /** @var SubstanceAmount|null amount Todo */
        public ?SubstanceAmount $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

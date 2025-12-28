<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The specific medication, food or laboratory test that interacts.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductInteraction', elementPath: 'MedicinalProductInteraction.interactant', fhirVersion: 'R5')]
class FHIRMedicinalProductInteractionInteractant extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|FHIRCodeableConcept|null itemX The specific medication, food or laboratory test that interacts */
        #[NotBlank]
        public \FHIRReference|\FHIRCodeableConcept|null $itemX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

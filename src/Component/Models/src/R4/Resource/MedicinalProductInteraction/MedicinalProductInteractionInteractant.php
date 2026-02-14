<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductInteraction;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The specific medication, food or laboratory test that interacts.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductInteraction', elementPath: 'MedicinalProductInteraction.interactant', fhirVersion: 'R4')]
class MedicinalProductInteractionInteractant extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|CodeableConcept|null itemX The specific medication, food or laboratory test that interacts */
        #[NotBlank]
        public Reference|CodeableConcept|null $itemX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

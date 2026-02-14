<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specifies if changes are allowed when dispensing a medication from a regulatory perspective.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory.substitution', fhirVersion: 'R4')]
class MedicationKnowledgeRegulatorySubstitution extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Specifies the type of substitution allowed */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var bool|null allowed Specifies if regulation allows for changes in the medication when dispensing */
        #[NotBlank]
        public ?bool $allowed = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

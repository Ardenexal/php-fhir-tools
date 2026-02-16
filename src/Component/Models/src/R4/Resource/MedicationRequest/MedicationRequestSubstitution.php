<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates whether or not substitution can or should be part of the dispense. In some cases, substitution must happen, in other cases substitution must not happen. This block explains the prescriber's intent. If nothing is specified substitution may be done.
 */
#[FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.substitution', fhirVersion: 'R4')]
class MedicationRequestSubstitution extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|CodeableConcept|null allowedX Whether substitution is allowed or not */
        #[NotBlank]
        public bool|CodeableConcept|null $allowedX = null,
        /** @var CodeableConcept|null reason Why should (not) substitution be made */
        public ?CodeableConcept $reason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

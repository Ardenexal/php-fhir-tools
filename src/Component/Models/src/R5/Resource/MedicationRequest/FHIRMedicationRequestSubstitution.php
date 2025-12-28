<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates whether or not substitution can or should be part of the dispense. In some cases, substitution must happen, in other cases substitution must not happen. This block explains the prescriber's intent. If nothing is specified substitution may be done.
 */
#[FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.substitution', fhirVersion: 'R5')]
class FHIRMedicationRequestSubstitution extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|FHIRCodeableConcept|null allowedX Whether substitution is allowed or not */
        #[NotBlank]
        public FHIRBoolean|FHIRCodeableConcept|null $allowedX = null,
        /** @var FHIRCodeableConcept|null reason Why should (not) substitution be made */
        public ?FHIRCodeableConcept $reason = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates whether or not substitution was made as part of the dispense.  In some cases, substitution will be expected but does not happen, in other cases substitution is not expected but does happen.  This block explains what substitution did or did not happen and why.  If nothing is specified, substitution was not done.
 */
#[FHIRBackboneElement(parentResource: 'MedicationDispense', elementPath: 'MedicationDispense.substitution', fhirVersion: 'R4')]
class FHIRMedicationDispenseSubstitution extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null wasSubstituted Whether a substitution was or was not performed on the dispense */
        #[NotBlank]
        public ?FHIRBoolean $wasSubstituted = null,
        /** @var FHIRCodeableConcept|null type Code signifying whether a different drug was dispensed from what was prescribed */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> reason Why was substitution made */
        public array $reason = [],
        /** @var array<FHIRReference> responsibleParty Who is responsible for the substitution */
        public array $responsibleParty = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

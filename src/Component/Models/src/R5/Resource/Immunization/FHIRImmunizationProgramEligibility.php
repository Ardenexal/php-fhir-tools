<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates a patient's eligibility for a funding program.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.programEligibility', fhirVersion: 'R5')]
class FHIRImmunizationProgramEligibility extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null program The program that eligibility is declared for */
        #[NotBlank]
        public ?FHIRCodeableConcept $program = null,
        /** @var FHIRCodeableConcept|null programStatus The patient's eligibility status for the program */
        #[NotBlank]
        public ?FHIRCodeableConcept $programStatus = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

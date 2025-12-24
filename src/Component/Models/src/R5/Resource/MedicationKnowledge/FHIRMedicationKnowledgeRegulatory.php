<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Regulatory information about a medication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeRegulatory extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null regulatoryAuthority Specifies the authority of the regulation */
        #[NotBlank]
        public ?FHIRReference $regulatoryAuthority = null,
        /** @var array<FHIRMedicationKnowledgeRegulatorySubstitution> substitution Specifies if changes are allowed when dispensing a medication from a regulatory perspective */
        public array $substitution = [],
        /** @var array<FHIRCodeableConcept> schedule Specifies the schedule of a medication in jurisdiction */
        public array $schedule = [],
        /** @var FHIRMedicationKnowledgeRegulatoryMaxDispense|null maxDispense The maximum number of units of the medication that can be dispensed in a period */
        public ?FHIRMedicationKnowledgeRegulatoryMaxDispense $maxDispense = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

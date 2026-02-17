<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Regulatory information about a medication.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory', fhirVersion: 'R4')]
class MedicationKnowledgeRegulatory extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null regulatoryAuthority Specifies the authority of the regulation */
        #[NotBlank]
        public ?Reference $regulatoryAuthority = null,
        /** @var array<MedicationKnowledgeRegulatorySubstitution> substitution Specifies if changes are allowed when dispensing a medication from a regulatory perspective */
        public array $substitution = [],
        /** @var array<MedicationKnowledgeRegulatorySchedule> schedule Specifies the schedule of a medication in jurisdiction */
        public array $schedule = [],
        /** @var MedicationKnowledgeRegulatoryMaxDispense|null maxDispense The maximum number of units of the medication that can be dispensed in a period */
        public ?MedicationKnowledgeRegulatoryMaxDispense $maxDispense = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

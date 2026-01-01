<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Regulatory information about a medication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory', fhirVersion: 'R4')]
class FHIRMedicationKnowledgeRegulatory extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference regulatoryAuthority Specifies the authority of the regulation */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $regulatoryAuthority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationKnowledgeRegulatorySubstitution> substitution Specifies if changes are allowed when dispensing a medication from a regulatory perspective */
		public array $substitution = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationKnowledgeRegulatorySchedule> schedule Specifies the schedule of a medication in jurisdiction */
		public array $schedule = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicationKnowledgeRegulatoryMaxDispense maxDispense The maximum number of units of the medication that can be dispensed in a period */
		public ?FHIRMedicationKnowledgeRegulatoryMaxDispense $maxDispense = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

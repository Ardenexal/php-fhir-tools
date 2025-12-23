<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicationKnowledge.indicationGuideline
 * @description Guidelines or protocols that are applicable for the administration of the medication based on indication.
 */
class FHIRMedicationKnowledgeIndicationGuideline extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> indication Indication for use that applies to the specific administration guideline */
		public array $indication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeIndicationGuidelineDosingGuideline> dosingGuideline Guidelines for dosage of the medication */
		public array $dosingGuideline = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

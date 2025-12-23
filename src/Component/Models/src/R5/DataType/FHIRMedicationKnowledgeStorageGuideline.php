<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicationKnowledge.storageGuideline
 * @description Information on how the medication should be stored, for example, refrigeration temperatures and length of stability at a given temperature.
 */
class FHIRMedicationKnowledgeStorageGuideline extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri reference Reference to additional information */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $reference = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Additional storage notes */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration stabilityDuration Duration remains stable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration $stabilityDuration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeStorageGuidelineEnvironmentalSetting> environmentalSetting Setting or value of environment for adequate storage */
		public array $environmentalSetting = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

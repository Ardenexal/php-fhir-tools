<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Information on how the medication should be stored, for example, refrigeration temperatures and length of stability at a given temperature.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.storageGuideline', fhirVersion: 'R5')]
class FHIRMedicationKnowledgeStorageGuideline extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri reference Reference to additional information */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $reference = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Additional storage notes */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration stabilityDuration Duration remains stable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration $stabilityDuration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicationKnowledgeStorageGuidelineEnvironmentalSetting> environmentalSetting Setting or value of environment for adequate storage */
		public array $environmentalSetting = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

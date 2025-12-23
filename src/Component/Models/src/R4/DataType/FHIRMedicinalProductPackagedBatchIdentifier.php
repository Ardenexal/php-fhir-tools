<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MedicinalProductPackaged.batchIdentifier
 * @description Batch numbering.
 */
class FHIRMedicinalProductPackagedBatchIdentifier extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier outerPackaging A number appearing on the outer packaging of a specific batch */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier $outerPackaging = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier immediatePackaging A number appearing on the immediate packaging (and not the outer packaging) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier $immediatePackaging = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

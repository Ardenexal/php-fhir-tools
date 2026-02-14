<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged;

/**
 * @description Batch numbering.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.batchIdentifier', fhirVersion: 'R4')]
class MedicinalProductPackagedBatchIdentifier extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier outerPackaging A number appearing on the outer packaging of a specific batch */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $outerPackaging = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier immediatePackaging A number appearing on the immediate packaging (and not the outer packaging) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $immediatePackaging = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

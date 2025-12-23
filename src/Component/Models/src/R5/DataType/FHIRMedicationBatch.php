<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Medication.batch
 * @description Information that only applies to packages (not products).
 */
class FHIRMedicationBatch extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string lotNumber Identifier assigned to batch */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime expirationDate When batch will expire */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $expirationDate = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

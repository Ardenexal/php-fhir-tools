<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Contract.term.asset.context
 * @description Circumstance of the asset.
 */
class FHIRContractTermAssetContext extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference reference Creator,custodian or owner */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $reference = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> code Codeable asset context */
		public array $code = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string text Context description */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $text = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

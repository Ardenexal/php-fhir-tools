<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Contract.rule
 * @description List of Computable Policy Rule Language Representations of this Contract.
 */
class FHIRContractRule extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference contentX Computable Contract Rules */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|null $contentX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

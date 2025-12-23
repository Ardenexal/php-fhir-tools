<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element AdverseEvent.mitigatingAction
 * @description The ameliorating action taken after the adverse event occured in order to reduce the extent of harm.
 */
class FHIRAdverseEventMitigatingAction extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept itemX Ameliorating action taken after the adverse event occured in order to reduce the extent of harm */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept|null $itemX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Immunization.reaction
 * @description Categorical data indicating that an adverse event is associated in time to an immunization.
 */
class FHIRImmunizationReaction extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime date When reaction started */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference detail Additional information on reaction */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $detail = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean reported Indicates self-reported reaction */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $reported = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element AllergyIntolerance.reaction
 * @description Details about each adverse reaction event linked to exposure to the identified substance.
 */
class FHIRAllergyIntoleranceReaction extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept substance Specific substance or pharmaceutical product considered to be responsible for event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $substance = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> manifestation Clinical symptoms/signs associated with the Event */
		public array $manifestation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Description of the event as a whole */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime onset Date(/time) when manifestations showed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $onset = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllergyIntoleranceSeverityType severity mild | moderate | severe (of event as a whole) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllergyIntoleranceSeverityType $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept exposureRoute How the subject was exposed to the substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $exposureRoute = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Text about event not captured in other fields */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

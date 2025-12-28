<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Details about each adverse reaction event linked to exposure to the identified substance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AllergyIntolerance', elementPath: 'AllergyIntolerance.reaction', fhirVersion: 'R4')]
class FHIRAllergyIntoleranceReaction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept substance Specific substance or pharmaceutical product considered to be responsible for event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $substance = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> manifestation Clinical symptoms/signs associated with the Event */
		public array $manifestation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string description Description of the event as a whole */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime onset Date(/time) when manifestations showed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $onset = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllergyIntoleranceSeverityType severity mild | moderate | severe (of event as a whole) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllergyIntoleranceSeverityType $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept exposureRoute How the subject was exposed to the substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $exposureRoute = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Text about event not captured in other fields */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

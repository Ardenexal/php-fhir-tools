<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization;

/**
 * @description Indicates who performed the immunization event.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.performer', fhirVersion: 'R4')]
class ImmunizationPerformer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept function What type of performance was done */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $function = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference actor Individual or organization who was performing */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $actor = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

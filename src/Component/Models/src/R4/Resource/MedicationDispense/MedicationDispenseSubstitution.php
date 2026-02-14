<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationDispense;

/**
 * @description Indicates whether or not substitution was made as part of the dispense.  In some cases, substitution will be expected but does not happen, in other cases substitution is not expected but does happen.  This block explains what substitution did or did not happen and why.  If nothing is specified, substitution was not done.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationDispense', elementPath: 'MedicationDispense.substitution', fhirVersion: 'R4')]
class MedicationDispenseSubstitution extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool wasSubstituted Whether a substitution was or was not performed on the dispense */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $wasSubstituted = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Code signifying whether a different drug was dispensed from what was prescribed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reason Why was substitution made */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> responsibleParty Who is responsible for the substitution */
		public array $responsibleParty = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

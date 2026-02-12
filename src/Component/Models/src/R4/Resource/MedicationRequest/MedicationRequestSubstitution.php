<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationRequest;

/**
 * @description Indicates whether or not substitution can or should be part of the dispense. In some cases, substitution must happen, in other cases substitution must not happen. This block explains the prescriber's intent. If nothing is specified substitution may be done.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationRequest', elementPath: 'MedicationRequest.substitution', fhirVersion: 'R4')]
class MedicationRequestSubstitution extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept allowedX Whether substitution is allowed or not */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $allowedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept reason Why should (not) substitution be made */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $reason = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim;

/**
 * @description Details of an accident which resulted in injuries which required the products and services listed in the claim.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.accident', fhirVersion: 'R4')]
class ClaimAccident extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive date When the incident occurred */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type The nature of the accident */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference locationX Where the event occurred */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Address|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $locationX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

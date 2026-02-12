<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent;

/**
 * @description Whether a treatment instruction (e.g. artificial respiration yes or no) was verified with the patient, his/her family or another authorized person.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.verification', fhirVersion: 'R4')]
class ConsentVerification extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool verified Has been verified */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $verified = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference verifiedWith Person who verified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $verifiedWith = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive verificationDate When consent verified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $verificationDate = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

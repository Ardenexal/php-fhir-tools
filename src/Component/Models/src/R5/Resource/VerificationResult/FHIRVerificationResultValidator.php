<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Information about the entity validating information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.validator', fhirVersion: 'R5')]
class FHIRVerificationResultValidator extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference organization Reference to the organization validating information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string identityCertificate A digital identity certificate associated with the validator */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $identityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSignature attestationSignature Validator signature (digital or image) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSignature $attestationSignature = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

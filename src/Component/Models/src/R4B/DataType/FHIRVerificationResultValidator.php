<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element VerificationResult.validator
 * @description Information about the entity validating information.
 */
class FHIRVerificationResultValidator extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference organization Reference to the organization validating information */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string identityCertificate A digital identity certificate associated with the validator */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $identityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSignature attestationSignature Validator signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSignature $attestationSignature = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

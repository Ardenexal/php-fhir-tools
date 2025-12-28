<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Information about the entity attesting to information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.attestation', fhirVersion: 'R4')]
class FHIRVerificationResultAttestation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference who The individual or organization attesting to information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference onBehalfOf When the who is asserting on behalf of another (organization or individual) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $onBehalfOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept communicationMethod The method by which attested information was submitted/retrieved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $communicationMethod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate date The date the information was attested to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string sourceIdentityCertificate A digital identity certificate associated with the attestation source */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $sourceIdentityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string proxyIdentityCertificate A digital identity certificate associated with the proxy entity submitting attested information on behalf of the attestation source */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $proxyIdentityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSignature proxySignature Proxy signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSignature $proxySignature = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSignature sourceSignature Attester signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSignature $sourceSignature = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

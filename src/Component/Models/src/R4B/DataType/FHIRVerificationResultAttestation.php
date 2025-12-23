<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element VerificationResult.attestation
 * @description Information about the entity attesting to information.
 */
class FHIRVerificationResultAttestation extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference who The individual or organization attesting to information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference onBehalfOf When the who is asserting on behalf of another (organization or individual) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $onBehalfOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept communicationMethod The method by which attested information was submitted/retrieved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $communicationMethod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate date The date the information was attested to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string sourceIdentityCertificate A digital identity certificate associated with the attestation source */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $sourceIdentityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string proxyIdentityCertificate A digital identity certificate associated with the proxy entity submitting attested information on behalf of the attestation source */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $proxyIdentityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSignature proxySignature Proxy signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSignature $proxySignature = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSignature sourceSignature Attester signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSignature $sourceSignature = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

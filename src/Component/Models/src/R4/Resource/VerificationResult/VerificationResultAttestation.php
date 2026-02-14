<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult;

/**
 * @description Information about the entity attesting to information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.attestation', fhirVersion: 'R4')]
class VerificationResultAttestation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference who The individual or organization attesting to information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference onBehalfOf When the who is asserting on behalf of another (organization or individual) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $onBehalfOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept communicationMethod The method by which attested information was submitted/retrieved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $communicationMethod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive date The date the information was attested to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string sourceIdentityCertificate A digital identity certificate associated with the attestation source */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $sourceIdentityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string proxyIdentityCertificate A digital identity certificate associated with the proxy entity submitting attested information on behalf of the attestation source */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $proxyIdentityCertificate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature proxySignature Proxy signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature $proxySignature = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature sourceSignature Attester signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature $sourceSignature = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

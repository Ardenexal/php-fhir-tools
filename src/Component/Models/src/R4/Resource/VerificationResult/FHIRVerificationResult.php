<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/VerificationResult
 * @description Describes validation requirements, source(s), status and dates for one or more elements.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'VerificationResult',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/VerificationResult',
	fhirVersion: 'R4',
)]
class FHIRVerificationResult extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> target A resource that was validated */
		public array $target = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string> targetLocation The fhirpath location(s) within the resource that was validated */
		public array $targetLocation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept need none | initial | periodic */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $need = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStatusType status attested | validated | in-process | req-revalid | val-fail | reval-fail */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime statusDate When the validation status was updated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $statusDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept validationType nothing | primary | multiple */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $validationType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> validationProcess The primary process by which the target is validated (edit check; value set; primary source; multiple sources; standalone; in context) */
		public array $validationProcess = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTiming frequency Frequency of revalidation */
		public ?FHIRTiming $frequency = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime lastPerformed The date/time validation was last completed (including failed validations) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $lastPerformed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate nextScheduled The date when target is next validated, if appropriate */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate $nextScheduled = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept failureAction fatal | warn | rec-only | none */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $failureAction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRVerificationResultPrimarySource> primarySource Information about the primary source(s) involved in validation */
		public array $primarySource = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRVerificationResultAttestation attestation Information about the entity attesting to information */
		public ?FHIRVerificationResultAttestation $attestation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRVerificationResultValidator> validator Information about the entity validating information */
		public array $validator = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

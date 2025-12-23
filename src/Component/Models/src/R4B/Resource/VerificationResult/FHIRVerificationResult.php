<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/VerificationResult
 * @description Describes validation requirements, source(s), status and dates for one or more elements.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'VerificationResult',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/VerificationResult',
	fhirVersion: 'R4B',
)]
class FHIRVerificationResult extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> target A resource that was validated */
		public array $target = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string> targetLocation The fhirpath location(s) within the resource that was validated */
		public array $targetLocation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept need none | initial | periodic */
		public ?FHIRCodeableConcept $need = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRStatusType status attested | validated | in-process | req-revalid | val-fail | reval-fail */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime statusDate When the validation status was updated */
		public ?FHIRDateTime $statusDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept validationType nothing | primary | multiple */
		public ?FHIRCodeableConcept $validationType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> validationProcess The primary process by which the target is validated (edit check; value set; primary source; multiple sources; standalone; in context) */
		public array $validationProcess = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTiming frequency Frequency of revalidation */
		public ?FHIRTiming $frequency = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime lastPerformed The date/time validation was last completed (including failed validations) */
		public ?FHIRDateTime $lastPerformed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate nextScheduled The date when target is next validated, if appropriate */
		public ?FHIRDate $nextScheduled = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept failureAction fatal | warn | rec-only | none */
		public ?FHIRCodeableConcept $failureAction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRVerificationResultPrimarySource> primarySource Information about the primary source(s) involved in validation */
		public array $primarySource = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRVerificationResultAttestation attestation Information about the entity attesting to information */
		public ?FHIRVerificationResultAttestation $attestation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRVerificationResultValidator> validator Information about the entity validating information */
		public array $validator = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

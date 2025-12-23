<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Media
 * @description A photo, video, or audio recording acquired or used in healthcare. The actual content may be inline or provided by direct reference.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Media', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Media', fhirVersion: 'R4')]
class FHIRMedia extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Identifier(s) for the image */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> basedOn Procedure that caused this media to be created */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> partOf Part of referenced event */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREventStatusType status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIREventStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Classification of media as image, video, or audio */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept modality The type of acquisition equipment/process */
		public ?FHIRCodeableConcept $modality = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept view Imaging view, e.g. Lateral or Antero-posterior */
		public ?FHIRCodeableConcept $view = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference subject Who/What this Media is a record of */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference encounter Encounter associated with media */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod createdX When Media was collected */
		public FHIRDateTime|FHIRPeriod|null $createdX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInstant issued Date/Time this version was made available */
		public ?FHIRInstant $issued = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference operator The person who generated the image */
		public ?FHIRReference $operator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> reasonCode Why was event performed? */
		public array $reasonCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept bodySite Observed body part */
		public ?FHIRCodeableConcept $bodySite = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string deviceName Name of the device/manufacturer */
		public FHIRString|string|null $deviceName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference device Observing Device */
		public ?FHIRReference $device = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt height Height of the image in pixels (photo/video) */
		public ?FHIRPositiveInt $height = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt width Width of the image in pixels (photo/video) */
		public ?FHIRPositiveInt $width = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt frames Number of frames if > 1 (photo) */
		public ?FHIRPositiveInt $frames = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal duration Length in seconds (audio / video) */
		public ?FHIRDecimal $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment content Actual Media - reference or data */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAttachment $content = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAnnotation> note Comments made about the media */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

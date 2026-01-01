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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Identifier(s) for the image */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> basedOn Procedure that caused this media to be created */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> partOf Part of referenced event */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIREventStatusType status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIREventStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type Classification of media as image, video, or audio */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept modality The type of acquisition equipment/process */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $modality = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept view Imaging view, e.g. Lateral or Antero-posterior */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $view = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference subject Who/What this Media is a record of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference encounter Encounter associated with media */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod createdX When Media was collected */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod|null $createdX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant issued Date/Time this version was made available */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant $issued = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference operator The person who generated the image */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $operator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> reasonCode Why was event performed? */
		public array $reasonCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept bodySite Observed body part */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $bodySite = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string deviceName Name of the device/manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $deviceName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference device Observing Device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $device = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt height Height of the image in pixels (photo/video) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt $height = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt width Width of the image in pixels (photo/video) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt $width = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt frames Number of frames if > 1 (photo) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt $frames = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal duration Length in seconds (audio / video) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment content Actual Media - reference or data */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment $content = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Comments made about the media */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Imaging Integration)
 * @see http://hl7.org/fhir/StructureDefinition/ImagingSelection
 * @description A selection of DICOM SOP instances and/or frames within a single Study and Series. This might include additional specifics such as an image region, an Observation UID or a Segmentation Number, allowing linkage to an Observation Resource or transferring this information along with the ImagingStudy Resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ImagingSelection',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ImagingSelection',
	fhirVersion: 'R5',
)]
class FHIRImagingSelection extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business Identifier for Imaging Selection */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelectionStatusType status available | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRImagingSelectionStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject Subject of the selected instances */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant issued Date / Time when this imaging selection was created */
		public ?FHIRInstant $issued = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelectionPerformer> performer Selector of the instances (human or machine) */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn Associated request */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category Classifies the imaging selection */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Imaging Selection purpose text or code */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId studyUid DICOM Study Instance UID */
		public ?FHIRId $studyUid = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> derivedFrom The imaging study from which the imaging selection is derived */
		public array $derivedFrom = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> endpoint The network service providing retrieval for the images referenced in the imaging selection */
		public array $endpoint = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId seriesUid DICOM Series Instance UID */
		public ?FHIRId $seriesUid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt seriesNumber DICOM Series Number */
		public ?FHIRUnsignedInt $seriesNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId frameOfReferenceUid The Frame of Reference UID for the selected images */
		public ?FHIRId $frameOfReferenceUid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference bodySite Body part examined */
		public ?FHIRCodeableReference $bodySite = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> focus Related resource that is the focus for the imaging selection */
		public array $focus = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelectionInstance> instance The selected instances */
		public array $instance = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

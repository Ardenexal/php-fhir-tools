<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DeviceUseStatement
 * @description A record of a device being used by a patient where the record is the result of a report from the patient or another clinician.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DeviceUseStatement',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/DeviceUseStatement',
	fhirVersion: 'R5',
)]
class FHIRDeviceUseStatement extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier External identifier for this record */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn Fulfills plan, proposal or order */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceUseStatementStatusType status active | completed | entered-in-error + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDeviceUseStatementStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject Patient using device */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> derivedFrom Supporting information */
		public array $derivedFrom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime timingX How often  the device was used */
		public FHIRTiming|FHIRPeriod|FHIRDateTime|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime recordedOn When statement was recorded */
		public ?FHIRDateTime $recordedOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference source Who made the statement */
		public ?FHIRReference $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference device Reference to device used */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $device = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> reasonCode Why device was used */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> reasonReference Why was DeviceUseStatement performed? */
		public array $reasonReference = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept bodySite Target body site */
		public ?FHIRCodeableConcept $bodySite = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Addition details (comments, instructions) */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

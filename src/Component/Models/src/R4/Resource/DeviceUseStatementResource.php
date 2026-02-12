<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DeviceUseStatement
 * @description A record of a device being used by a patient where the record is the result of a report from the patient or another clinician.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DeviceUseStatement',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/DeviceUseStatement',
	fhirVersion: 'R4',
)]
class DeviceUseStatementResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External identifier for this record */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> basedOn Fulfills plan, proposal or order */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceUseStatementStatusType status active | completed | entered-in-error + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceUseStatementStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Patient using device */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> derivedFrom Supporting information */
		public array $derivedFrom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive timingX How often  the device was used */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive recordedOn When statement was recorded */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $recordedOn = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference source Who made the statement */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $source = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference device Reference to device used */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $device = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reasonCode Why device was used */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> reasonReference Why was DeviceUseStatement performed? */
		public array $reasonReference = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept bodySite Target body site */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $bodySite = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Addition details (comments, instructions) */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

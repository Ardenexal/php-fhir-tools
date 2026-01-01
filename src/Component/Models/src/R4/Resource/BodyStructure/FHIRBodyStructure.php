<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/BodyStructure
 * @description Record details about an anatomical structure.  This resource may be used when a coded concept does not provide the necessary detail needed for the use case.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'BodyStructure', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/BodyStructure', fhirVersion: 'R4')]
class FHIRBodyStructure extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Bodystructure identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean active Whether this record is in active use */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept morphology Kind of Structure */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $morphology = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept location Body site */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> locationQualifier Body site modifier */
		public array $locationQualifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string description Text description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment> image Attached images */
		public array $image = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference patient Who this is about */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $patient = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

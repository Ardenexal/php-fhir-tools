<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/GuidanceResponse
 * @description A guidance response is the formal response to a guidance request, including any output parameters returned by the evaluation, as well as the description of any proposed actions to be taken.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'GuidanceResponse',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/GuidanceResponse',
	fhirVersion: 'R4B',
)]
class FHIRGuidanceResponse extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier requestIdentifier The identifier of the request associated with this response, if any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier $requestIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier> identifier Business identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept moduleX What guidance was requested */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept|null $moduleX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRGuidanceResponseStatusType status success | data-requested | data-required | in-progress | failure | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRGuidanceResponseStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference subject Patient the request was performed for */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference encounter Encounter during which the response was returned */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime occurrenceDateTime When the guidance response was processed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $occurrenceDateTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference performer Device returning the guidance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $performer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> reasonCode Why guidance is needed */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> reasonReference Why guidance is needed */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation> note Additional notes about the response */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> evaluationMessage Messages resulting from the evaluation of the artifact or artifacts */
		public array $evaluationMessage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference outputParameters The output parameters of the evaluation, if any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $outputParameters = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference result Proposed actions, if any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $result = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDataRequirement> dataRequirement Additional required data */
		public array $dataRequirement = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

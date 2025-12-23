<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/GuidanceResponse
 * @description A guidance response is the formal response to a guidance request, including any output parameters returned by the evaluation, as well as the description of any proposed actions to be taken.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'GuidanceResponse',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/GuidanceResponse',
	fhirVersion: 'R4',
)]
class FHIRGuidanceResponse extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier requestIdentifier The identifier of the request associated with this response, if any */
		public ?FHIRIdentifier $requestIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Business identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept moduleX What guidance was requested */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRUri|FHIRCanonical|FHIRCodeableConcept|null $moduleX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRGuidanceResponseStatusType status success | data-requested | data-required | in-progress | failure | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRGuidanceResponseStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference subject Patient the request was performed for */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference encounter Encounter during which the response was returned */
		public ?FHIRReference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime occurrenceDateTime When the guidance response was processed */
		public ?FHIRDateTime $occurrenceDateTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference performer Device returning the guidance */
		public ?FHIRReference $performer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> reasonCode Why guidance is needed */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> reasonReference Why guidance is needed */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAnnotation> note Additional notes about the response */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> evaluationMessage Messages resulting from the evaluation of the artifact or artifacts */
		public array $evaluationMessage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference outputParameters The output parameters of the evaluation, if any */
		public ?FHIRReference $outputParameters = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference result Proposed actions, if any */
		public ?FHIRReference $result = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDataRequirement> dataRequirement Additional required data */
		public array $dataRequirement = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

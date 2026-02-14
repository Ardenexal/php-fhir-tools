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
class GuidanceResponseResource extends DomainResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier requestIdentifier The identifier of the request associated with this response, if any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $requestIdentifier = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept moduleX What guidance was requested */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $moduleX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GuidanceResponseStatusType status success | data-requested | data-required | in-progress | failure | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GuidanceResponseStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject Patient the request was performed for */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference encounter Encounter during which the response was returned */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $encounter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive occurrenceDateTime When the guidance response was processed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $occurrenceDateTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference performer Device returning the guidance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $performer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reasonCode Why guidance is needed */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> reasonReference Why guidance is needed */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Additional notes about the response */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> evaluationMessage Messages resulting from the evaluation of the artifact or artifacts */
		public array $evaluationMessage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference outputParameters The output parameters of the evaluation, if any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $outputParameters = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference result Proposed actions, if any */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $result = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement> dataRequirement Additional required data */
		public array $dataRequirement = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

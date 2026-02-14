<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Community Based Collaborative Care)
 * @see http://hl7.org/fhir/StructureDefinition/Consent
 * @description A record of a healthcare consumer’s  choices, which permits or denies identified recipient(s) or recipient role(s) to perform one or more actions within a given policy context, for specific purposes and periods of time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Consent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Consent', fhirVersion: 'R4')]
class ConsentResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Identifier for this record (external references) */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConsentStateType status draft | proposed | active | rejected | inactive | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConsentStateType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept scope Which of the four areas this resource covers (extensible) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $scope = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Classification of the consent statement - for indexing/retrieval */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient Who the consent applies to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive dateTime When this Consent was created or indexed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $dateTime = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> performer Who is agreeing to the policy and rules */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> organization Custodian of the consent */
		public array $organization = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference sourceX Source from which this consent is taken */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $sourceX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentPolicy> policy Policies covered by this consent */
		public array $policy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept policyRule Regulation that this consents to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $policyRule = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentVerification> verification Consent Verified by patient or family */
		public array $verification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentProvision provision Constraints to the base Consent.policyRule */
		public ?Consent\ConsentProvision $provision = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

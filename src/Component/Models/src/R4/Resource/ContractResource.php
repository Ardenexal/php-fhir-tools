<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/Contract
 * @description Legally enforceable, formally recorded unilateral or bilateral directive i.e., a policy or agreement.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Contract', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Contract', fhirVersion: 'R4')]
class ContractResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Contract number */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Basal definition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Business edition */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContractResourceStatusCodesType status amended | appended | cancelled | disputed | entered-in-error | executable | executed | negotiable | offered | policy | rejected | renewed | revoked | resolved | terminated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContractResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept legalState Negotiation status */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $legalState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference instantiatesCanonical Source Contract Definition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $instantiatesCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive instantiatesUri External Contract Definition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $instantiatesUri = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept contentDerivative Content derived from the basal information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $contentDerivative = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive issued When this Contract was issued */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $issued = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period applies Effective time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $applies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept expirationType Contract cessation cause */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $expirationType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> subject Contract Target Entity */
		public array $subject = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> authority Authority under which this Contract has standing */
		public array $authority = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> domain A sphere of control governed by an authoritative jurisdiction, organization, or person */
		public array $domain = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> site Specific Location */
		public array $site = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Computer friendly designation */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Human Friendly name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string subtitle Subordinate Friendly name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $subtitle = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> alias Acronym or short name */
		public array $alias = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference author Source of Contract */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept scope Range of Legal Concerns */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $scope = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference topicX Focus of contract interest */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $topicX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Legal instrument category */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> subType Subtype within the context of type */
		public array $subType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractContentDefinition contentDefinition Contract precursor content */
		public ?Contract\ContractContentDefinition $contentDefinition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTerm> term Contract Term List */
		public array $term = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> supportingInfo Extra Information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> relevantHistory Key event in Contract History */
		public array $relevantHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractSigner> signer Contract Signatory */
		public array $signer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractFriendly> friendly Contract Friendly Language */
		public array $friendly = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractLegal> legal Contract Legal Language */
		public array $legal = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractRule> rule Computable Contract Language */
		public array $rule = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference legallyBindingX Binding Contract */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $legallyBindingX = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

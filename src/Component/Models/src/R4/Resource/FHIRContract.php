<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/Contract
 * @description Legally enforceable, formally recorded unilateral or bilateral directive i.e., a policy or agreement.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Contract', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Contract', fhirVersion: 'R4')]
class FHIRContract extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Contract number */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri url Basal definition */
		public ?FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string version Business edition */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContractResourceStatusCodesType status amended | appended | cancelled | disputed | entered-in-error | executable | executed | negotiable | offered | policy | rejected | renewed | revoked | resolved | terminated */
		public ?FHIRContractResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept legalState Negotiation status */
		public ?FHIRCodeableConcept $legalState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference instantiatesCanonical Source Contract Definition */
		public ?FHIRReference $instantiatesCanonical = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri instantiatesUri External Contract Definition */
		public ?FHIRUri $instantiatesUri = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept contentDerivative Content derived from the basal information */
		public ?FHIRCodeableConcept $contentDerivative = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime issued When this Contract was issued */
		public ?FHIRDateTime $issued = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod applies Effective time */
		public ?FHIRPeriod $applies = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept expirationType Contract cessation cause */
		public ?FHIRCodeableConcept $expirationType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> subject Contract Target Entity */
		public array $subject = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> authority Authority under which this Contract has standing */
		public array $authority = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> domain A sphere of control governed by an authoritative jurisdiction, organization, or person */
		public array $domain = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> site Specific Location */
		public array $site = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name Computer friendly designation */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string title Human Friendly name */
		public FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string subtitle Subordinate Friendly name */
		public FHIRString|string|null $subtitle = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string> alias Acronym or short name */
		public array $alias = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference author Source of Contract */
		public ?FHIRReference $author = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept scope Range of Legal Concerns */
		public ?FHIRCodeableConcept $scope = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference topicX Focus of contract interest */
		public FHIRCodeableConcept|FHIRReference|null $topicX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type Legal instrument category */
		public ?FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> subType Subtype within the context of type */
		public array $subType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContractContentDefinition contentDefinition Contract precursor content */
		public ?FHIRContractContentDefinition $contentDefinition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContractTerm> term Contract Term List */
		public array $term = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> supportingInfo Extra Information */
		public array $supportingInfo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> relevantHistory Key event in Contract History */
		public array $relevantHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContractSigner> signer Contract Signatory */
		public array $signer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContractFriendly> friendly Contract Friendly Language */
		public array $friendly = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContractLegal> legal Contract Legal Language */
		public array $legal = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContractRule> rule Computable Contract Language */
		public array $rule = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference legallyBindingX Binding Contract */
		public FHIRAttachment|FHIRReference|null $legallyBindingX = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

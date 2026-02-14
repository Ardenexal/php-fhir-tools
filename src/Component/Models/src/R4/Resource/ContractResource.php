<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContractResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractContentDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractFriendly;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractLegal;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractRule;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractSigner;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract\ContractTerm;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Contract
 *
 * @description Legally enforceable, formally recorded unilateral or bilateral directive i.e., a policy or agreement.
 */
#[FhirResource(type: 'Contract', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Contract', fhirVersion: 'R4')]
class ContractResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Contract number */
        public array $identifier = [],
        /** @var UriPrimitive|null url Basal definition */
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business edition */
        public StringPrimitive|string|null $version = null,
        /** @var ContractResourceStatusCodesType|null status amended | appended | cancelled | disputed | entered-in-error | executable | executed | negotiable | offered | policy | rejected | renewed | revoked | resolved | terminated */
        public ?ContractResourceStatusCodesType $status = null,
        /** @var CodeableConcept|null legalState Negotiation status */
        public ?CodeableConcept $legalState = null,
        /** @var Reference|null instantiatesCanonical Source Contract Definition */
        public ?Reference $instantiatesCanonical = null,
        /** @var UriPrimitive|null instantiatesUri External Contract Definition */
        public ?UriPrimitive $instantiatesUri = null,
        /** @var CodeableConcept|null contentDerivative Content derived from the basal information */
        public ?CodeableConcept $contentDerivative = null,
        /** @var DateTimePrimitive|null issued When this Contract was issued */
        public ?DateTimePrimitive $issued = null,
        /** @var Period|null applies Effective time */
        public ?Period $applies = null,
        /** @var CodeableConcept|null expirationType Contract cessation cause */
        public ?CodeableConcept $expirationType = null,
        /** @var array<Reference> subject Contract Target Entity */
        public array $subject = [],
        /** @var array<Reference> authority Authority under which this Contract has standing */
        public array $authority = [],
        /** @var array<Reference> domain A sphere of control governed by an authoritative jurisdiction, organization, or person */
        public array $domain = [],
        /** @var array<Reference> site Specific Location */
        public array $site = [],
        /** @var StringPrimitive|string|null name Computer friendly designation */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Human Friendly name */
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null subtitle Subordinate Friendly name */
        public StringPrimitive|string|null $subtitle = null,
        /** @var array<StringPrimitive|string> alias Acronym or short name */
        public array $alias = [],
        /** @var Reference|null author Source of Contract */
        public ?Reference $author = null,
        /** @var CodeableConcept|null scope Range of Legal Concerns */
        public ?CodeableConcept $scope = null,
        /** @var CodeableConcept|Reference|null topicX Focus of contract interest */
        public CodeableConcept|Reference|null $topicX = null,
        /** @var CodeableConcept|null type Legal instrument category */
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> subType Subtype within the context of type */
        public array $subType = [],
        /** @var ContractContentDefinition|null contentDefinition Contract precursor content */
        public ?ContractContentDefinition $contentDefinition = null,
        /** @var array<ContractTerm> term Contract Term List */
        public array $term = [],
        /** @var array<Reference> supportingInfo Extra Information */
        public array $supportingInfo = [],
        /** @var array<Reference> relevantHistory Key event in Contract History */
        public array $relevantHistory = [],
        /** @var array<ContractSigner> signer Contract Signatory */
        public array $signer = [],
        /** @var array<ContractFriendly> friendly Contract Friendly Language */
        public array $friendly = [],
        /** @var array<ContractLegal> legal Contract Legal Language */
        public array $legal = [],
        /** @var array<ContractRule> rule Computable Contract Language */
        public array $rule = [],
        /** @var Attachment|Reference|null legallyBindingX Binding Contract */
        public Attachment|Reference|null $legallyBindingX = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

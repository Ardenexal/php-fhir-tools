<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Contract
 *
 * @description Legally enforceable, formally recorded unilateral or bilateral directive i.e., a policy or agreement.
 */
#[FhirResource(type: 'Contract', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Contract', fhirVersion: 'R4')]
class FHIRContract extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Contract number */
        public array $identifier = [],
        /** @var FHIRUri|null url Basal definition */
        public ?FHIRUri $url = null,
        /** @var FHIRString|string|null version Business edition */
        public FHIRString|string|null $version = null,
        /** @var FHIRContractResourceStatusCodesType|null status amended | appended | cancelled | disputed | entered-in-error | executable | executed | negotiable | offered | policy | rejected | renewed | revoked | resolved | terminated */
        public ?FHIRContractResourceStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|null legalState Negotiation status */
        public ?FHIRCodeableConcept $legalState = null,
        /** @var FHIRReference|null instantiatesCanonical Source Contract Definition */
        public ?FHIRReference $instantiatesCanonical = null,
        /** @var FHIRUri|null instantiatesUri External Contract Definition */
        public ?FHIRUri $instantiatesUri = null,
        /** @var FHIRCodeableConcept|null contentDerivative Content derived from the basal information */
        public ?FHIRCodeableConcept $contentDerivative = null,
        /** @var FHIRDateTime|null issued When this Contract was issued */
        public ?FHIRDateTime $issued = null,
        /** @var FHIRPeriod|null applies Effective time */
        public ?FHIRPeriod $applies = null,
        /** @var FHIRCodeableConcept|null expirationType Contract cessation cause */
        public ?FHIRCodeableConcept $expirationType = null,
        /** @var array<FHIRReference> subject Contract Target Entity */
        public array $subject = [],
        /** @var array<FHIRReference> authority Authority under which this Contract has standing */
        public array $authority = [],
        /** @var array<FHIRReference> domain A sphere of control governed by an authoritative jurisdiction, organization, or person */
        public array $domain = [],
        /** @var array<FHIRReference> site Specific Location */
        public array $site = [],
        /** @var FHIRString|string|null name Computer friendly designation */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Human Friendly name */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null subtitle Subordinate Friendly name */
        public FHIRString|string|null $subtitle = null,
        /** @var array<FHIRString|string> alias Acronym or short name */
        public array $alias = [],
        /** @var FHIRReference|null author Source of Contract */
        public ?FHIRReference $author = null,
        /** @var FHIRCodeableConcept|null scope Range of Legal Concerns */
        public ?FHIRCodeableConcept $scope = null,
        /** @var FHIRCodeableConcept|FHIRReference|null topicX Focus of contract interest */
        public FHIRCodeableConcept|FHIRReference|null $topicX = null,
        /** @var FHIRCodeableConcept|null type Legal instrument category */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> subType Subtype within the context of type */
        public array $subType = [],
        /** @var FHIRContractContentDefinition|null contentDefinition Contract precursor content */
        public ?FHIRContractContentDefinition $contentDefinition = null,
        /** @var array<FHIRContractTerm> term Contract Term List */
        public array $term = [],
        /** @var array<FHIRReference> supportingInfo Extra Information */
        public array $supportingInfo = [],
        /** @var array<FHIRReference> relevantHistory Key event in Contract History */
        public array $relevantHistory = [],
        /** @var array<FHIRContractSigner> signer Contract Signatory */
        public array $signer = [],
        /** @var array<FHIRContractFriendly> friendly Contract Friendly Language */
        public array $friendly = [],
        /** @var array<FHIRContractLegal> legal Contract Legal Language */
        public array $legal = [],
        /** @var array<FHIRContractRule> rule Computable Contract Language */
        public array $rule = [],
        /** @var FHIRAttachment|FHIRReference|null legallyBindingX Binding Contract */
        public FHIRAttachment|FHIRReference|null $legallyBindingX = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

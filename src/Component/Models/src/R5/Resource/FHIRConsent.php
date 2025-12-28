<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Community Based Collaborative Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Consent
 *
 * @description A record of a healthcare consumer’s  choices  or choices made on their behalf by a third party, which permits or denies identified recipient(s) or recipient role(s) to perform one or more actions within a given policy context, for specific purposes and periods of time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Consent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Consent', fhirVersion: 'R5')]
class FHIRConsent extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifier for this record (external references) */
        public array $identifier = [],
        /** @var FHIRConsentStateType|null status draft | active | inactive | not-done | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRConsentStateType $status = null,
        /** @var array<FHIRCodeableConcept> category Classification of the consent statement - for indexing/retrieval */
        public array $category = [],
        /** @var FHIRReference|null subject Who the consent applies to */
        public ?\FHIRReference $subject = null,
        /** @var FHIRDate|null date Fully executed date of the consent */
        public ?\FHIRDate $date = null,
        /** @var FHIRPeriod|null period Effective period for this Consent */
        public ?\FHIRPeriod $period = null,
        /** @var array<FHIRReference> grantor Who is granting rights according to the policy and rules */
        public array $grantor = [],
        /** @var array<FHIRReference> grantee Who is agreeing to the policy and rules */
        public array $grantee = [],
        /** @var array<FHIRReference> manager Consent workflow management */
        public array $manager = [],
        /** @var array<FHIRReference> controller Consent Enforcer */
        public array $controller = [],
        /** @var array<FHIRAttachment> sourceAttachment Source from which this consent is taken */
        public array $sourceAttachment = [],
        /** @var array<FHIRReference> sourceReference Source from which this consent is taken */
        public array $sourceReference = [],
        /** @var array<FHIRCodeableConcept> regulatoryBasis Regulations establishing base Consent */
        public array $regulatoryBasis = [],
        /** @var FHIRConsentPolicyBasis|null policyBasis Computable version of the backing policy */
        public ?\FHIRConsentPolicyBasis $policyBasis = null,
        /** @var array<FHIRReference> policyText Human Readable Policy */
        public array $policyText = [],
        /** @var array<FHIRConsentVerification> verification Consent Verified by patient or family */
        public array $verification = [],
        /** @var FHIRConsentProvisionTypeType|null decision deny | permit */
        public ?\FHIRConsentProvisionTypeType $decision = null,
        /** @var array<FHIRConsentProvision> provision Constraints to the base Consent.policyRule/Consent.policy */
        public array $provision = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

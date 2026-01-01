<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRConsentStateType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Community Based Collaborative Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Consent
 *
 * @description A record of a healthcare consumer’s  choices, which permits or denies identified recipient(s) or recipient role(s) to perform one or more actions within a given policy context, for specific purposes and periods of time.
 */
#[FhirResource(type: 'Consent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Consent', fhirVersion: 'R4')]
class FHIRConsent extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Identifier for this record (external references) */
        public array $identifier = [],
        /** @var FHIRConsentStateType|null status draft | proposed | active | rejected | inactive | entered-in-error */
        #[NotBlank]
        public ?FHIRConsentStateType $status = null,
        /** @var FHIRCodeableConcept|null scope Which of the four areas this resource covers (extensible) */
        #[NotBlank]
        public ?FHIRCodeableConcept $scope = null,
        /** @var array<FHIRCodeableConcept> category Classification of the consent statement - for indexing/retrieval */
        public array $category = [],
        /** @var FHIRReference|null patient Who the consent applies to */
        public ?FHIRReference $patient = null,
        /** @var FHIRDateTime|null dateTime When this Consent was created or indexed */
        public ?FHIRDateTime $dateTime = null,
        /** @var array<FHIRReference> performer Who is agreeing to the policy and rules */
        public array $performer = [],
        /** @var array<FHIRReference> organization Custodian of the consent */
        public array $organization = [],
        /** @var FHIRAttachment|FHIRReference|null sourceX Source from which this consent is taken */
        public FHIRAttachment|FHIRReference|null $sourceX = null,
        /** @var array<FHIRConsentPolicy> policy Policies covered by this consent */
        public array $policy = [],
        /** @var FHIRCodeableConcept|null policyRule Regulation that this consents to */
        public ?FHIRCodeableConcept $policyRule = null,
        /** @var array<FHIRConsentVerification> verification Consent Verified by patient or family */
        public array $verification = [],
        /** @var FHIRConsentProvision|null provision Constraints to the base Consent.policyRule */
        public ?FHIRConsentProvision $provision = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

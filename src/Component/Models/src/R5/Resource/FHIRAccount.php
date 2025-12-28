<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Account
 *
 * @description A financial tool for tracking value accrued for a particular purpose.  In the healthcare field, used to track charges for a patient, cost centers, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Account', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Account', fhirVersion: 'R5')]
class FHIRAccount extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Account number */
        public array $identifier = [],
        /** @var FHIRAccountStatusType|null status active | inactive | entered-in-error | on-hold | unknown */
        #[NotBlank]
        public ?\FHIRAccountStatusType $status = null,
        /** @var FHIRCodeableConcept|null billingStatus Tracks the lifecycle of the account through the billing process */
        public ?\FHIRCodeableConcept $billingStatus = null,
        /** @var FHIRCodeableConcept|null type E.g. patient, expense, depreciation */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null name Human-readable label */
        public \FHIRString|string|null $name = null,
        /** @var array<FHIRReference> subject The entity that caused the expenses */
        public array $subject = [],
        /** @var FHIRPeriod|null servicePeriod Transaction window */
        public ?\FHIRPeriod $servicePeriod = null,
        /** @var array<FHIRAccountCoverage> coverage The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account */
        public array $coverage = [],
        /** @var FHIRReference|null owner Entity managing the Account */
        public ?\FHIRReference $owner = null,
        /** @var FHIRMarkdown|null description Explanation of purpose/use */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRAccountGuarantor> guarantor The parties ultimately responsible for balancing the Account */
        public array $guarantor = [],
        /** @var array<FHIRAccountDiagnosis> diagnosis The list of diagnoses relevant to this account */
        public array $diagnosis = [],
        /** @var array<FHIRAccountProcedure> procedure The list of procedures relevant to this account */
        public array $procedure = [],
        /** @var array<FHIRAccountRelatedAccount> relatedAccount Other associated accounts related to this account */
        public array $relatedAccount = [],
        /** @var FHIRCodeableConcept|null currency The base or default currency */
        public ?\FHIRCodeableConcept $currency = null,
        /** @var array<FHIRAccountBalance> balance Calculated account balance(s) */
        public array $balance = [],
        /** @var FHIRInstant|null calculatedAt Time the balance amount was calculated */
        public ?\FHIRInstant $calculatedAt = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

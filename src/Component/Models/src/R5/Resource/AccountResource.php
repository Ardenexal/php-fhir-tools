<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AccountStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountBalance;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountCoverage;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountGuarantor;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountProcedure;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountRelatedAccount;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Account
 *
 * @description A financial tool for tracking value accrued for a particular purpose.  In the healthcare field, used to track charges for a patient, cost centers, etc.
 */
#[FhirResource(type: 'Account', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Account', fhirVersion: 'R5')]
class AccountResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Account number */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var AccountStatusType|null status active | inactive | entered-in-error | on-hold | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?AccountStatusType $status = null,
        /** @var CodeableConcept|null billingStatus Tracks the lifecycle of the account through the billing process */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $billingStatus = null,
        /** @var CodeableConcept|null type E.g. patient, expense, depreciation */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var StringPrimitive|string|null name Human-readable label */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var array<Reference> subject The entity that caused the expenses */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $subject = [],
        /** @var Period|null servicePeriod Transaction window */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $servicePeriod = null,
        /** @var array<AccountCoverage> coverage The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountCoverage',
        )]
        public array $coverage = [],
        /** @var Reference|null owner Entity managing the Account */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $owner = null,
        /** @var MarkdownPrimitive|null description Explanation of purpose/use */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var array<AccountGuarantor> guarantor The parties ultimately responsible for balancing the Account */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountGuarantor',
        )]
        public array $guarantor = [],
        /** @var array<AccountDiagnosis> diagnosis The list of diagnoses relevant to this account */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountDiagnosis',
        )]
        public array $diagnosis = [],
        /** @var array<AccountProcedure> procedure The list of procedures relevant to this account */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountProcedure',
        )]
        public array $procedure = [],
        /** @var array<AccountRelatedAccount> relatedAccount Other associated accounts related to this account */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountRelatedAccount',
        )]
        public array $relatedAccount = [],
        /** @var CodeableConcept|null currency The base or default currency */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $currency = null,
        /** @var array<AccountBalance> balance Calculated account balance(s) */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Account\AccountBalance',
        )]
        public array $balance = [],
        /** @var InstantPrimitive|null calculatedAt Time the balance amount was calculated */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $calculatedAt = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

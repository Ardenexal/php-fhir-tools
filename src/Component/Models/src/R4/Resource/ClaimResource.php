<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimUseType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimAccident;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimCareTeam;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimInsurance;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimPayee;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimProcedure;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimRelated;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimSupportingInfo;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Claim
 *
 * @description A provider issued list of professional services and products which have been provided, or are to be provided, to a patient which is sent to an insurer for reimbursement.
 */
#[FhirResource(type: 'Claim', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Claim', fhirVersion: 'R4')]
class ClaimResource extends DomainResourceResource
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
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
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
        /** @var array<Identifier> identifier Business Identifier for claim */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var CodeableConcept|null type Category or discipline */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null subType More granular claim type */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $subType = null,
        /** @var ClaimUseType|null use claim | preauthorization | predetermination */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ClaimUseType $use = null,
        /** @var Reference|null patient The recipient of the products and services */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $patient = null,
        /** @var Period|null billablePeriod Relevant time frame for the claim */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $billablePeriod = null,
        /** @var DateTimePrimitive|null created Resource creation date */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null enterer Author of the claim */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $enterer = null,
        /** @var Reference|null insurer Target */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $insurer = null,
        /** @var Reference|null provider Party responsible for the claim */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $provider = null,
        /** @var CodeableConcept|null priority Desired processing ugency */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $priority = null,
        /** @var CodeableConcept|null fundsReserve For whom to reserve funds */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $fundsReserve = null,
        /** @var array<ClaimRelated> related Prior or corollary claims */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimRelated',
        )]
        public array $related = [],
        /** @var Reference|null prescription Prescription authorizing services and products */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $prescription = null,
        /** @var Reference|null originalPrescription Original prescription if superseded by fulfiller */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $originalPrescription = null,
        /** @var ClaimPayee|null payee Recipient of benefits payable */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ClaimPayee $payee = null,
        /** @var Reference|null referral Treatment referral */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $referral = null,
        /** @var Reference|null facility Servicing facility */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $facility = null,
        /** @var array<ClaimCareTeam> careTeam Members of the care team */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimCareTeam',
        )]
        public array $careTeam = [],
        /** @var array<ClaimSupportingInfo> supportingInfo Supporting information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimSupportingInfo',
        )]
        public array $supportingInfo = [],
        /** @var array<ClaimDiagnosis> diagnosis Pertinent diagnosis information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimDiagnosis',
        )]
        public array $diagnosis = [],
        /** @var array<ClaimProcedure> procedure Clinical procedures performed */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimProcedure',
        )]
        public array $procedure = [],
        /** @var array<ClaimInsurance> insurance Patient insurance information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimInsurance',
        )]
        public array $insurance = [],
        /** @var ClaimAccident|null accident Details of the event */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ClaimAccident $accident = null,
        /** @var array<ClaimItem> item Product or service provided */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim\ClaimItem',
        )]
        public array $item = [],
        /** @var Money|null total Total claim cost */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $total = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

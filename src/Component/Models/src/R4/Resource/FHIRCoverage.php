<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Coverage
 *
 * @description Financial instrument which may be used to reimburse or pay for health care products and services. Includes both insurance and self-payment.
 */
#[FhirResource(type: 'Coverage', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Coverage', fhirVersion: 'R4')]
class FHIRCoverage extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for the coverage */
        public array $identifier = [],
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|null type Coverage category such as medical or accident */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRReference|null policyHolder Owner of the policy */
        public ?FHIRReference $policyHolder = null,
        /** @var FHIRReference|null subscriber Subscriber to the policy */
        public ?FHIRReference $subscriber = null,
        /** @var FHIRString|string|null subscriberId ID assigned to the subscriber */
        public FHIRString|string|null $subscriberId = null,
        /** @var FHIRReference|null beneficiary Plan beneficiary */
        #[NotBlank]
        public ?FHIRReference $beneficiary = null,
        /** @var FHIRString|string|null dependent Dependent number */
        public FHIRString|string|null $dependent = null,
        /** @var FHIRCodeableConcept|null relationship Beneficiary relationship to the subscriber */
        public ?FHIRCodeableConcept $relationship = null,
        /** @var FHIRPeriod|null period Coverage start and end dates */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRReference> payor Issuer of the policy */
        public array $payor = [],
        /** @var array<FHIRCoverageClass> class Additional coverage classifications */
        public array $class = [],
        /** @var FHIRPositiveInt|null order Relative order of the coverage */
        public ?FHIRPositiveInt $order = null,
        /** @var FHIRString|string|null network Insurer network */
        public FHIRString|string|null $network = null,
        /** @var array<FHIRCoverageCostToBeneficiary> costToBeneficiary Patient payments for services/products */
        public array $costToBeneficiary = [],
        /** @var FHIRBoolean|null subrogation Reimbursement to insurer */
        public ?FHIRBoolean $subrogation = null,
        /** @var array<FHIRReference> contract Contract details */
        public array $contract = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

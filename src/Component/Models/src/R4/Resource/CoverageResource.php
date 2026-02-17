<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Coverage\CoverageClass;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Coverage\CoverageCostToBeneficiary;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Coverage
 *
 * @description Financial instrument which may be used to reimburse or pay for health care products and services. Includes both insurance and self-payment.
 */
#[FhirResource(type: 'Coverage', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Coverage', fhirVersion: 'R4')]
class CoverageResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for the coverage */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var CodeableConcept|null type Coverage category such as medical or accident */
        public ?CodeableConcept $type = null,
        /** @var Reference|null policyHolder Owner of the policy */
        public ?Reference $policyHolder = null,
        /** @var Reference|null subscriber Subscriber to the policy */
        public ?Reference $subscriber = null,
        /** @var StringPrimitive|string|null subscriberId ID assigned to the subscriber */
        public StringPrimitive|string|null $subscriberId = null,
        /** @var Reference|null beneficiary Plan beneficiary */
        #[NotBlank]
        public ?Reference $beneficiary = null,
        /** @var StringPrimitive|string|null dependent Dependent number */
        public StringPrimitive|string|null $dependent = null,
        /** @var CodeableConcept|null relationship Beneficiary relationship to the subscriber */
        public ?CodeableConcept $relationship = null,
        /** @var Period|null period Coverage start and end dates */
        public ?Period $period = null,
        /** @var array<Reference> payor Issuer of the policy */
        public array $payor = [],
        /** @var array<CoverageClass> class Additional coverage classifications */
        public array $class = [],
        /** @var PositiveIntPrimitive|null order Relative order of the coverage */
        public ?PositiveIntPrimitive $order = null,
        /** @var StringPrimitive|string|null network Insurer network */
        public StringPrimitive|string|null $network = null,
        /** @var array<CoverageCostToBeneficiary> costToBeneficiary Patient payments for services/products */
        public array $costToBeneficiary = [],
        /** @var bool|null subrogation Reimbursement to insurer */
        public ?bool $subrogation = null,
        /** @var array<Reference> contract Contract details */
        public array $contract = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

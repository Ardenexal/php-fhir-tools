<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A claim line. Either a simple  product or service or a 'group' of details which can each be a simple items or groups of sub-details.
 */
#[FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.item', fhirVersion: 'R4')]
class FHIRClaimItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Item instance identifier */
        #[NotBlank]
        public ?\FHIRPositiveInt $sequence = null,
        /** @var array<FHIRPositiveInt> careTeamSequence Applicable careTeam members */
        public array $careTeamSequence = [],
        /** @var array<FHIRPositiveInt> diagnosisSequence Applicable diagnoses */
        public array $diagnosisSequence = [],
        /** @var array<FHIRPositiveInt> procedureSequence Applicable procedures */
        public array $procedureSequence = [],
        /** @var array<FHIRPositiveInt> informationSequence Applicable exception and supporting information */
        public array $informationSequence = [],
        /** @var FHIRCodeableConcept|null revenue Revenue or cost center code */
        public ?\FHIRCodeableConcept $revenue = null,
        /** @var FHIRCodeableConcept|null category Benefit classification */
        public ?\FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        #[NotBlank]
        public ?\FHIRCodeableConcept $productOrService = null,
        /** @var array<FHIRCodeableConcept> modifier Product or service billing modifiers */
        public array $modifier = [],
        /** @var array<FHIRCodeableConcept> programCode Program the product or service is provided under */
        public array $programCode = [],
        /** @var FHIRDate|FHIRPeriod|null servicedX Date or dates of service or product delivery */
        public \FHIRDate|\FHIRPeriod|null $servicedX = null,
        /** @var FHIRCodeableConcept|FHIRAddress|FHIRReference|null locationX Place of service or where product was supplied */
        public \FHIRCodeableConcept|\FHIRAddress|\FHIRReference|null $locationX = null,
        /** @var FHIRQuantity|null quantity Count of products or services */
        public ?\FHIRQuantity $quantity = null,
        /** @var FHIRMoney|null unitPrice Fee, charge or cost per item */
        public ?\FHIRMoney $unitPrice = null,
        /** @var FHIRDecimal|null factor Price scaling factor */
        public ?\FHIRDecimal $factor = null,
        /** @var FHIRMoney|null net Total item cost */
        public ?\FHIRMoney $net = null,
        /** @var array<FHIRReference> udi Unique device identifier */
        public array $udi = [],
        /** @var FHIRCodeableConcept|null bodySite Anatomical location */
        public ?\FHIRCodeableConcept $bodySite = null,
        /** @var array<FHIRCodeableConcept> subSite Anatomical sub-location */
        public array $subSite = [],
        /** @var array<FHIRReference> encounter Encounters related to this billed item */
        public array $encounter = [],
        /** @var array<FHIRClaimItemDetail> detail Product or service provided */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

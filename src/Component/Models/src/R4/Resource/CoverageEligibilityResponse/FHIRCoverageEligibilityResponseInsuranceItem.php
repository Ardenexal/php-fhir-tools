<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Benefits and optionally current balances, and authorization details by category or service.
 */
#[FHIRBackboneElement(
    parentResource: 'CoverageEligibilityResponse',
    elementPath: 'CoverageEligibilityResponse.insurance.item',
    fhirVersion: 'R4',
)]
class FHIRCoverageEligibilityResponseInsuranceItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category Benefit classification */
        public ?\FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        public ?\FHIRCodeableConcept $productOrService = null,
        /** @var array<FHIRCodeableConcept> modifier Product or service billing modifiers */
        public array $modifier = [],
        /** @var FHIRReference|null provider Performing practitioner */
        public ?\FHIRReference $provider = null,
        /** @var FHIRBoolean|null excluded Excluded from the plan */
        public ?\FHIRBoolean $excluded = null,
        /** @var FHIRString|string|null name Short name for the benefit */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null description Description of the benefit or services covered */
        public \FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null network In or out of network */
        public ?\FHIRCodeableConcept $network = null,
        /** @var FHIRCodeableConcept|null unit Individual or family */
        public ?\FHIRCodeableConcept $unit = null,
        /** @var FHIRCodeableConcept|null term Annual or lifetime */
        public ?\FHIRCodeableConcept $term = null,
        /** @var array<FHIRCoverageEligibilityResponseInsuranceItemBenefit> benefit Benefit Summary */
        public array $benefit = [],
        /** @var FHIRBoolean|null authorizationRequired Authorization required flag */
        public ?\FHIRBoolean $authorizationRequired = null,
        /** @var array<FHIRCodeableConcept> authorizationSupporting Type of required supporting materials */
        public array $authorizationSupporting = [],
        /** @var FHIRUri|null authorizationUrl Preauthorization requirements endpoint */
        public ?\FHIRUri $authorizationUrl = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

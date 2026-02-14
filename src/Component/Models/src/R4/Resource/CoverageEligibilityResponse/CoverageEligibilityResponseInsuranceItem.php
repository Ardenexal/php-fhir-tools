<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description Benefits and optionally current balances, and authorization details by category or service.
 */
#[FHIRBackboneElement(
    parentResource: 'CoverageEligibilityResponse',
    elementPath: 'CoverageEligibilityResponse.insurance.item',
    fhirVersion: 'R4',
)]
class CoverageEligibilityResponseInsuranceItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null category Benefit classification */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        public ?CodeableConcept $productOrService = null,
        /** @var array<CodeableConcept> modifier Product or service billing modifiers */
        public array $modifier = [],
        /** @var Reference|null provider Performing practitioner */
        public ?Reference $provider = null,
        /** @var bool|null excluded Excluded from the plan */
        public ?bool $excluded = null,
        /** @var StringPrimitive|string|null name Short name for the benefit */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null description Description of the benefit or services covered */
        public StringPrimitive|string|null $description = null,
        /** @var CodeableConcept|null network In or out of network */
        public ?CodeableConcept $network = null,
        /** @var CodeableConcept|null unit Individual or family */
        public ?CodeableConcept $unit = null,
        /** @var CodeableConcept|null term Annual or lifetime */
        public ?CodeableConcept $term = null,
        /** @var array<CoverageEligibilityResponseInsuranceItemBenefit> benefit Benefit Summary */
        public array $benefit = [],
        /** @var bool|null authorizationRequired Authorization required flag */
        public ?bool $authorizationRequired = null,
        /** @var array<CodeableConcept> authorizationSupporting Type of required supporting materials */
        public array $authorizationSupporting = [],
        /** @var UriPrimitive|null authorizationUrl Preauthorization requirements endpoint */
        public ?UriPrimitive $authorizationUrl = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

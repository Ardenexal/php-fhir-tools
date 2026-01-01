<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;

/**
 * @description Service categories or billable services for which benefit details and/or an authorization prior to service delivery may be required by the payor.
 */
#[FHIRBackboneElement(parentResource: 'CoverageEligibilityRequest', elementPath: 'CoverageEligibilityRequest.item', fhirVersion: 'R5')]
class FHIRCoverageEligibilityRequestItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRPositiveInt> supportingInfoSequence Applicable exception or supporting information */
        public array $supportingInfoSequence = [],
        /** @var FHIRCodeableConcept|null category Benefit classification */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null productOrService Billing, service, product, or drug code */
        public ?FHIRCodeableConcept $productOrService = null,
        /** @var array<FHIRCodeableConcept> modifier Product or service billing modifiers */
        public array $modifier = [],
        /** @var FHIRReference|null provider Perfoming practitioner */
        public ?FHIRReference $provider = null,
        /** @var FHIRQuantity|null quantity Count of products or services */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRMoney|null unitPrice Fee, charge or cost per item */
        public ?FHIRMoney $unitPrice = null,
        /** @var FHIRReference|null facility Servicing facility */
        public ?FHIRReference $facility = null,
        /** @var array<FHIRCoverageEligibilityRequestItemDiagnosis> diagnosis Applicable diagnosis */
        public array $diagnosis = [],
        /** @var array<FHIRReference> detail Product or service details */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

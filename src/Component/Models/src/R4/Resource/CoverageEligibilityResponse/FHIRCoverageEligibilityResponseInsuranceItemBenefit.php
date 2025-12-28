<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Benefits used to date.
 */
#[FHIRBackboneElement(
    parentResource: 'CoverageEligibilityResponse',
    elementPath: 'CoverageEligibilityResponse.insurance.item.benefit',
    fhirVersion: 'R4',
)]
class FHIRCoverageEligibilityResponseInsuranceItemBenefit extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Benefit classification */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRUnsignedInt|FHIRString|string|FHIRMoney|null allowedX Benefits allowed */
        public \FHIRUnsignedInt|\FHIRString|string|\FHIRMoney|null $allowedX = null,
        /** @var FHIRUnsignedInt|FHIRString|string|FHIRMoney|null usedX Benefits used */
        public \FHIRUnsignedInt|\FHIRString|string|\FHIRMoney|null $usedX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

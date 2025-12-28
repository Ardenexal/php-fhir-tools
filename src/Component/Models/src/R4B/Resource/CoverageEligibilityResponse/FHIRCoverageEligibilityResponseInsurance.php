<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Financial instruments for reimbursement for the health care products and services.
 */
#[FHIRBackboneElement(parentResource: 'CoverageEligibilityResponse', elementPath: 'CoverageEligibilityResponse.insurance', fhirVersion: 'R4B')]
class FHIRCoverageEligibilityResponseInsurance extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null coverage Insurance information */
        #[NotBlank]
        public ?\FHIRReference $coverage = null,
        /** @var FHIRBoolean|null inforce Coverage inforce indicator */
        public ?\FHIRBoolean $inforce = null,
        /** @var FHIRPeriod|null benefitPeriod When the benefits are applicable */
        public ?\FHIRPeriod $benefitPeriod = null,
        /** @var array<FHIRCoverageEligibilityResponseInsuranceItem> item Benefits and authorization details */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

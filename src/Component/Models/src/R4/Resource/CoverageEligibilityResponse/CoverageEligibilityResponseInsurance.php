<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Financial instruments for reimbursement for the health care products and services.
 */
#[FHIRBackboneElement(parentResource: 'CoverageEligibilityResponse', elementPath: 'CoverageEligibilityResponse.insurance', fhirVersion: 'R4')]
class CoverageEligibilityResponseInsurance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null coverage Insurance information */
        #[NotBlank]
        public ?Reference $coverage = null,
        /** @var bool|null inforce Coverage inforce indicator */
        public ?bool $inforce = null,
        /** @var Period|null benefitPeriod When the benefits are applicable */
        public ?Period $benefitPeriod = null,
        /** @var array<CoverageEligibilityResponseInsuranceItem> item Benefits and authorization details */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

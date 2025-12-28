<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Financial instruments for reimbursement for the health care products and services.
 */
#[FHIRBackboneElement(parentResource: 'CoverageEligibilityRequest', elementPath: 'CoverageEligibilityRequest.insurance', fhirVersion: 'R4')]
class FHIRCoverageEligibilityRequestInsurance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null focal Applicable coverage */
        public ?\FHIRBoolean $focal = null,
        /** @var FHIRReference|null coverage Insurance information */
        #[NotBlank]
        public ?\FHIRReference $coverage = null,
        /** @var FHIRString|string|null businessArrangement Additional provider contract number */
        public \FHIRString|string|null $businessArrangement = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

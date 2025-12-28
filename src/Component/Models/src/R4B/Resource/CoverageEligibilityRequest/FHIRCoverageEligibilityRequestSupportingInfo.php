<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional information codes regarding exceptions, special considerations, the condition, situation, prior or concurrent issues.
 */
#[FHIRBackboneElement(
    parentResource: 'CoverageEligibilityRequest',
    elementPath: 'CoverageEligibilityRequest.supportingInfo',
    fhirVersion: 'R4B',
)]
class FHIRCoverageEligibilityRequestSupportingInfo extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Information instance identifier */
        #[NotBlank]
        public ?FHIRPositiveInt $sequence = null,
        /** @var FHIRReference|null information Data to be provided */
        #[NotBlank]
        public ?FHIRReference $information = null,
        /** @var FHIRBoolean|null appliesToAll Applies to all items */
        public ?FHIRBoolean $appliesToAll = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information code for an event with a corresponding date or period.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CoverageEligibilityResponse', elementPath: 'CoverageEligibilityResponse.event', fhirVersion: 'R5')]
class FHIRCoverageEligibilityResponseEvent extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Specific event */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRDateTime|FHIRPeriod|null whenX Occurance date or period */
        #[NotBlank]
        public FHIRDateTime|FHIRPeriod|null $whenX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

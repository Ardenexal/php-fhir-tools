<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description The high-level results of the adjudication if adjudication has been performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item.reviewOutcome', fhirVersion: 'R5')]
class FHIRExplanationOfBenefitItemReviewOutcome extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null decision Result of the adjudication */
        public ?FHIRCodeableConcept $decision = null,
        /** @var array<FHIRCodeableConcept> reason Reason for result of the adjudication */
        public array $reason = [],
        /** @var FHIRString|string|null preAuthRef Preauthorization reference */
        public FHIRString|string|null $preAuthRef = null,
        /** @var FHIRPeriod|null preAuthPeriod Preauthorization reference effective period */
        public ?FHIRPeriod $preAuthPeriod = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

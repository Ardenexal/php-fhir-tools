<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Some observations have multiple component observations.  These component observations are expressed as separate code value pairs that share the same attributes.  Examples include systolic and diastolic component observations for blood pressure measurement and multiple component observations for genetics observations.
 */
#[FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.component', fhirVersion: 'R4')]
class FHIRObservationComponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Type of component observation (code / type) */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRQuantity|FHIRCodeableConcept|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRRange|FHIRRatio|FHIRSampledData|FHIRTime|FHIRDateTime|FHIRPeriod|null valueX Actual component result */
        public \FHIRQuantity|\FHIRCodeableConcept|\FHIRString|string|\FHIRBoolean|\FHIRInteger|\FHIRRange|\FHIRRatio|\FHIRSampledData|\FHIRTime|\FHIRDateTime|\FHIRPeriod|null $valueX = null,
        /** @var FHIRCodeableConcept|null dataAbsentReason Why the component result is missing */
        public ?\FHIRCodeableConcept $dataAbsentReason = null,
        /** @var array<FHIRCodeableConcept> interpretation High, low, normal, etc. */
        public array $interpretation = [],
        /** @var array<FHIRObservationReferenceRange> referenceRange Provides guide for interpretation of component result */
        public array $referenceRange = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

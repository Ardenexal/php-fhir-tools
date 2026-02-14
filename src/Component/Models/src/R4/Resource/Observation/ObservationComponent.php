<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Observation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SampledData;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Some observations have multiple component observations.  These component observations are expressed as separate code value pairs that share the same attributes.  Examples include systolic and diastolic component observations for blood pressure measurement and multiple component observations for genetics observations.
 */
#[FHIRBackboneElement(parentResource: 'Observation', elementPath: 'Observation.component', fhirVersion: 'R4')]
class ObservationComponent extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Type of component observation (code / type) */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Quantity|CodeableConcept|StringPrimitive|string|bool|int|Range|Ratio|SampledData|TimePrimitive|DateTimePrimitive|Period|null valueX Actual component result */
        public Quantity|CodeableConcept|StringPrimitive|string|bool|int|Range|Ratio|SampledData|TimePrimitive|DateTimePrimitive|Period|null $valueX = null,
        /** @var CodeableConcept|null dataAbsentReason Why the component result is missing */
        public ?CodeableConcept $dataAbsentReason = null,
        /** @var array<CodeableConcept> interpretation High, low, normal, etc. */
        public array $interpretation = [],
        /** @var array<ObservationReferenceRange> referenceRange Provides guide for interpretation of component result */
        public array $referenceRange = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

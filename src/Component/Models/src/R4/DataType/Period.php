<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Period
 *
 * @description A time period defined by a start and end date and optionally time.
 */
#[FHIRComplexType(typeName: 'Period', fhirVersion: 'R4')]
class Period extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var DateTimePrimitive|null start Starting time with inclusive boundary */
        public ?DateTimePrimitive $start = null,
        /** @var DateTimePrimitive|null end End time with inclusive boundary, if not ongoing */
        public ?DateTimePrimitive $end = null,
    ) {
        parent::__construct($id, $extension);
    }
}

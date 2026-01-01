<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Period
 *
 * @description A time period defined by a start and end date and optionally time.
 */
#[FHIRComplexType(typeName: 'Period', fhirVersion: 'R5')]
class FHIRPeriod extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRDateTime|null start Starting time with inclusive boundary */
        public ?FHIRDateTime $start = null,
        /** @var FHIRDateTime|null end End time with inclusive boundary, if not ongoing */
        public ?FHIRDateTime $end = null,
    ) {
        parent::__construct($id, $extension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/time
 *
 * @description A time during the day, with no date specified
 */
#[FHIRPrimitive(primitiveType: 'time', fhirVersion: 'R4')]
class FHIRTime extends FHIRElement
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null value Primitive value for time */
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/positiveInt
 *
 * @description An integer with a value that is positive (e.g. >0)
 */
#[FHIRPrimitive(primitiveType: 'positiveInt', fhirVersion: 'R4')]
class PositiveIntPrimitive extends IntegerPrimitive
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var int|null value Primitive value for positiveInt */
        public ?int $value = null,
    ) {
        parent::__construct($id, $extension, $value);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPrimitiveType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/decimal
 *
 * @description A rational number with implicit precision
 */
#[FHIRPrimitive(primitiveType: 'decimal', fhirVersion: 'R5')]
class FHIRDecimal extends FHIRPrimitiveType
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var float|null value Primitive value for decimal */
        public ?float $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}

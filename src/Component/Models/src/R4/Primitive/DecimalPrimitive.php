<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Element;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/decimal
 *
 * @description A rational number with implicit precision
 */
#[FHIRPrimitive(primitiveType: 'decimal', fhirVersion: 'R4')]
class DecimalPrimitive extends Element
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var float|null value Primitive value for decimal */
        public ?float $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}

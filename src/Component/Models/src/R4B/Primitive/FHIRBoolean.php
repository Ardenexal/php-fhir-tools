<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/boolean
 *
 * @description Value of "true" or "false"
 */
#[FHIRPrimitive(primitiveType: 'boolean', fhirVersion: 'R4B')]
class FHIRBoolean extends FHIRElement
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var bool|null value Primitive value for boolean */
        public ?bool $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}

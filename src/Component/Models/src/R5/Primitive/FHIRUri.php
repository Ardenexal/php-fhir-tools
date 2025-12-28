<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPrimitiveType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/uri
 *
 * @description String of characters used to identify a name or a resource
 */
#[FHIRPrimitive(primitiveType: 'uri', fhirVersion: 'R5')]
class FHIRUri extends FHIRPrimitiveType
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null value Primitive value for uri */
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}

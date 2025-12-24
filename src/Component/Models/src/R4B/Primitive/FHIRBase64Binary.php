<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/base64Binary
 *
 * @description A stream of bytes
 */
#[FHIRPrimitive(primitiveType: 'base64Binary', fhirVersion: 'R4B')]
class FHIRBase64Binary extends FHIRElement
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null value Primitive value for base64Binary */
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}

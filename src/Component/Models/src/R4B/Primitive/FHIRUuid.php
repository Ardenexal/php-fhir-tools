<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/uuid
 *
 * @description A UUID, represented as a URI
 */
#[FHIRPrimitive(primitiveType: 'uuid', fhirVersion: 'R4B')]
class FHIRUuid extends FHIRUri
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null value Primitive value for uuid */
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension, $value);
    }
}

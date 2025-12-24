<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPrimitiveType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/instant
 *
 * @description An instant in time - known at least to the second
 */
#[FHIRPrimitive(primitiveType: 'instant', fhirVersion: 'R5')]
class FHIRInstant extends FHIRPrimitiveType
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var \DateTimeInterface|null value Primitive value for instant */
        public ?\DateTimeInterface $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}

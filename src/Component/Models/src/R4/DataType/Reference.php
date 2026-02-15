<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Reference
 *
 * @description A reference from one resource to another.
 */
#[FHIRComplexType(typeName: 'Reference', fhirVersion: 'R4')]
class Reference extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var StringPrimitive|string|null reference Literal reference, Relative, internal or absolute URL */
        public StringPrimitive|string|null $reference = null,
        /** @var UriPrimitive|null type Type the reference refers to (e.g. "Patient") */
        public ?UriPrimitive $type = null,
        /** @var Identifier|null identifier Logical reference, when literal reference is not known */
        public ?Identifier $identifier = null,
        /** @var StringPrimitive|string|null display Text alternative for the resource */
        public StringPrimitive|string|null $display = null,
    ) {
        parent::__construct($id, $extension);
    }
}

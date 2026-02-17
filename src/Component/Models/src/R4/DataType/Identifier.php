<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Identifier
 *
 * @description An identifier - identifies some entity uniquely and unambiguously. Typically this is used for business identifiers.
 */
#[FHIRComplexType(typeName: 'Identifier', fhirVersion: 'R4')]
class Identifier extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var IdentifierUseType|null use usual | official | temp | secondary | old (If known) */
        public ?IdentifierUseType $use = null,
        /** @var CodeableConcept|null type Description of identifier */
        public ?CodeableConcept $type = null,
        /** @var UriPrimitive|null system The namespace for the identifier value */
        public ?UriPrimitive $system = null,
        /** @var StringPrimitive|string|null value The value that is unique */
        public StringPrimitive|string|null $value = null,
        /** @var Period|null period Time period when id is/was valid for use */
        public ?Period $period = null,
        /** @var Reference|null assigner Organization that issued id (may be just text) */
        public ?Reference $assigner = null,
    ) {
        parent::__construct($id, $extension);
    }
}

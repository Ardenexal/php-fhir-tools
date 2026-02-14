<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ContactDetail
 *
 * @description Specifies contact information for a person or organization.
 */
#[FHIRComplexType(typeName: 'ContactDetail', fhirVersion: 'R4')]
class ContactDetail extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var StringPrimitive|string|null name Name of an individual to contact */
        public StringPrimitive|string|null $name = null,
        /** @var array<ContactPoint> telecom Contact details for individual or organization */
        public array $telecom = [],
    ) {
        parent::__construct($id, $extension);
    }
}

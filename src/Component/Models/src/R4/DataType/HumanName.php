<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/HumanName
 *
 * @description A human's name with the ability to identify parts and usage.
 */
#[FHIRComplexType(typeName: 'HumanName', fhirVersion: 'R4')]
class HumanName extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var NameUseType|null use usual | official | temp | nickname | anonymous | old | maiden */
        public ?NameUseType $use = null,
        /** @var StringPrimitive|string|null text Text representation of the full name */
        public StringPrimitive|string|null $text = null,
        /** @var StringPrimitive|string|null family Family name (often called 'Surname') */
        public StringPrimitive|string|null $family = null,
        /** @var array<StringPrimitive|string> given Given names (not always 'first'). Includes middle names */
        public array $given = [],
        /** @var array<StringPrimitive|string> prefix Parts that come before the name */
        public array $prefix = [],
        /** @var array<StringPrimitive|string> suffix Parts that come after the name */
        public array $suffix = [],
        /** @var Period|null period Time period when name was/is in use */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}

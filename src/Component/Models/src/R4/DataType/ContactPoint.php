<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ContactPoint
 *
 * @description Details for all kinds of technology mediated contact points for a person or organization, including telephone, email, etc.
 */
#[FHIRComplexType(typeName: 'ContactPoint', fhirVersion: 'R4')]
class ContactPoint extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var ContactPointSystemType|null system phone | fax | email | pager | url | sms | other */
        public ?ContactPointSystemType $system = null,
        /** @var StringPrimitive|string|null value The actual contact point details */
        public StringPrimitive|string|null $value = null,
        /** @var ContactPointUseType|null use home | work | temp | old | mobile - purpose of this contact point */
        public ?ContactPointUseType $use = null,
        /** @var PositiveIntPrimitive|null rank Specify preferred order of use (1 = highest) */
        public ?PositiveIntPrimitive $rank = null,
        /** @var Period|null period Time period when the contact point was/is in use */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}

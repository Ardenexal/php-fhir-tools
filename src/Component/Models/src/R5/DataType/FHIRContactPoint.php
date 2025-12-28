<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ContactPoint
 *
 * @description Details for all kinds of technology mediated contact points for a person or organization, including telephone, email, etc.
 */
#[FHIRComplexType(typeName: 'ContactPoint', fhirVersion: 'R5')]
class FHIRContactPoint extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRContactPointSystemType|null system phone | fax | email | pager | url | sms | other */
        public ?\FHIRContactPointSystemType $system = null,
        /** @var FHIRString|string|null value The actual contact point details */
        public \FHIRString|string|null $value = null,
        /** @var FHIRContactPointUseType|null use home | work | temp | old | mobile - purpose of this contact point */
        public ?\FHIRContactPointUseType $use = null,
        /** @var FHIRPositiveInt|null rank Specify preferred order of use (1 = highest) */
        public ?\FHIRPositiveInt $rank = null,
        /** @var FHIRPeriod|null period Time period when the contact point was/is in use */
        public ?\FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}

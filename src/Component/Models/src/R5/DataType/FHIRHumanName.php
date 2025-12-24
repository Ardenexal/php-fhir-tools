<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNameUseType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/HumanName
 *
 * @description A name, normally of a human, that can be used for other living entities (e.g. animals but not organizations) that have been assigned names by a human and may need the use of name parts or the need for usage information.
 */
#[FHIRComplexType(typeName: 'HumanName', fhirVersion: 'R5')]
class FHIRHumanName extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRNameUseType|null use usual | official | temp | nickname | anonymous | old | maiden */
        public ?FHIRNameUseType $use = null,
        /** @var FHIRString|string|null text Text representation of the full name */
        public FHIRString|string|null $text = null,
        /** @var FHIRString|string|null family Family name (often called 'Surname') */
        public FHIRString|string|null $family = null,
        /** @var array<FHIRString|string> given Given names (not always 'first'). Includes middle names */
        public array $given = [],
        /** @var array<FHIRString|string> prefix Parts that come before the name */
        public array $prefix = [],
        /** @var array<FHIRString|string> suffix Parts that come after the name */
        public array $suffix = [],
        /** @var FHIRPeriod|null period Time period when name was/is in use */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}

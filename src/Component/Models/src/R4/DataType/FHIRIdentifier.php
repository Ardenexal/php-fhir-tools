<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Identifier
 *
 * @description An identifier - identifies some entity uniquely and unambiguously. Typically this is used for business identifiers.
 */
#[FHIRComplexType(typeName: 'Identifier', fhirVersion: 'R4')]
class FHIRIdentifier extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRIdentifierUseType|null use usual | official | temp | secondary | old (If known) */
        public ?FHIRIdentifierUseType $use = null,
        /** @var FHIRCodeableConcept|null type Description of identifier */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRUri|null system The namespace for the identifier value */
        public ?FHIRUri $system = null,
        /** @var FHIRString|string|null value The value that is unique */
        public FHIRString|string|null $value = null,
        /** @var FHIRPeriod|null period Time period when id is/was valid for use */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null assigner Organization that issued id (may be just text) */
        public ?FHIRReference $assigner = null,
    ) {
        parent::__construct($id, $extension);
    }
}

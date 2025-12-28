<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/CodeableConcept
 *
 * @description A concept that may be defined by a formal reference to a terminology or ontology or may be provided by text.
 */
#[FHIRComplexType(typeName: 'CodeableConcept', fhirVersion: 'R4')]
class FHIRCodeableConcept extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRCoding> coding Code defined by a terminology system */
        public array $coding = [],
        /** @var FHIRString|string|null text Plain text representation of the concept */
        public \FHIRString|string|null $text = null,
    ) {
        parent::__construct($id, $extension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/CodeableReference
 *
 * @description A reference to a resource (by instance), or instead, a reference to a concept defined in a terminology or ontology (by class).
 */
#[FHIRComplexType(typeName: 'CodeableReference', fhirVersion: 'R4B')]
class FHIRCodeableReference extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRCodeableConcept|null concept Reference to a concept (by class) */
        public ?FHIRCodeableConcept $concept = null,
        /** @var FHIRReference|null reference Reference to a resource (by instance) */
        public ?FHIRReference $reference = null,
    ) {
        parent::__construct($id, $extension);
    }
}

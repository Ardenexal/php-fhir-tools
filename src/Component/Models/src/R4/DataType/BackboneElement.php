<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/BackboneElement
 *
 * @description Base definition for all elements that are defined inside a resource - but not those in a data type.
 */
#[FHIRComplexType(typeName: 'BackboneElement', fhirVersion: 'R4')]
abstract class BackboneElement extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
    ) {
        parent::__construct($id, $extension);
    }
}

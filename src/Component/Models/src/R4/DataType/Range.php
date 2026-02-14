<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Range
 *
 * @description A set of ordered Quantities defined by a low and high limit.
 */
#[FHIRComplexType(typeName: 'Range', fhirVersion: 'R4')]
class Range extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var Quantity|null low Low limit */
        public ?Quantity $low = null,
        /** @var Quantity|null high High limit */
        public ?Quantity $high = null,
    ) {
        parent::__construct($id, $extension);
    }
}

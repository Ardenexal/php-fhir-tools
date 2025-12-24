<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Range
 *
 * @description A set of ordered Quantities defined by a low and high limit.
 */
#[FHIRComplexType(typeName: 'Range', fhirVersion: 'R4B')]
class FHIRRange extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRQuantity|null low Low limit */
        public ?FHIRQuantity $low = null,
        /** @var FHIRQuantity|null high High limit */
        public ?FHIRQuantity $high = null,
    ) {
        parent::__construct($id, $extension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description A snapshot view is expressed in a standalone form that can be used and interpreted without considering the base StructureDefinition.
 */
#[FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.snapshot', fhirVersion: 'R4')]
class StructureDefinitionSnapshot extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<ElementDefinition> element Definition of elements in the resource (if no StructureDefinition) */
        public array $element = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

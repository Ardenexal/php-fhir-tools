<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;

/**
 * @description A differential view is expressed relative to the base StructureDefinition - a statement of differences that it applies.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.differential', fhirVersion: 'R4')]
class FHIRStructureDefinitionDifferential extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRElementDefinition> element Definition of elements in the resource (if no StructureDefinition) */
        public array $element = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

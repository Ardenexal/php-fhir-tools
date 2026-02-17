<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ConceptMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description A group of mappings that all have the same source and target system.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group', fhirVersion: 'R4')]
class ConceptMapGroup extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null source Source system where concepts to be mapped are defined */
        public ?UriPrimitive $source = null,
        /** @var StringPrimitive|string|null sourceVersion Specific version of the  code system */
        public StringPrimitive|string|null $sourceVersion = null,
        /** @var UriPrimitive|null target Target system that the concepts are to be mapped to */
        public ?UriPrimitive $target = null,
        /** @var StringPrimitive|string|null targetVersion Specific version of the  code system */
        public StringPrimitive|string|null $targetVersion = null,
        /** @var array<ConceptMapGroupElement> element Mappings for a concept from the source set */
        public array $element = [],
        /** @var ConceptMapGroupUnmapped|null unmapped What to do when there is no mapping for the source concept */
        public ?ConceptMapGroupUnmapped $unmapped = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ConceptMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Mappings for an individual concept in the source to one or more concepts in the target.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element', fhirVersion: 'R4')]
class ConceptMapGroupElement extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null code Identifies element being mapped */
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Display for the code */
        public StringPrimitive|string|null $display = null,
        /** @var array<ConceptMapGroupElementTarget> target Concept in target system for element */
        public array $target = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

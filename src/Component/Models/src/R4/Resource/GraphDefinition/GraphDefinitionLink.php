<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\GraphDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Links this graph makes rules about.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link', fhirVersion: 'R4')]
class GraphDefinitionLink extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null path Path in the resource that contains the link */
        public StringPrimitive|string|null $path = null,
        /** @var StringPrimitive|string|null sliceName Which slice (if profiled) */
        public StringPrimitive|string|null $sliceName = null,
        /** @var int|null min Minimum occurrences for this link */
        public ?int $min = null,
        /** @var StringPrimitive|string|null max Maximum occurrences for this link */
        public StringPrimitive|string|null $max = null,
        /** @var StringPrimitive|string|null description Why this link is specified */
        public StringPrimitive|string|null $description = null,
        /** @var array<GraphDefinitionLinkTarget> target Potential target for the link */
        public array $target = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

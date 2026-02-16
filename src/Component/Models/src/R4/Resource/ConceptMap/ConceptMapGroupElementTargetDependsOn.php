<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ConceptMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A set of additional dependencies for this mapping to hold. This mapping is only applicable if the specified element can be resolved, and it has the specified value.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target.dependsOn', fhirVersion: 'R4')]
class ConceptMapGroupElementTargetDependsOn extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null property Reference to property mapping depends on */
        #[NotBlank]
        public ?UriPrimitive $property = null,
        /** @var CanonicalPrimitive|null system Code System (if necessary) */
        public ?CanonicalPrimitive $system = null,
        /** @var StringPrimitive|string|null value Value of the referenced element */
        #[NotBlank]
        public StringPrimitive|string|null $value = null,
        /** @var StringPrimitive|string|null display Display for the code (if value is a code) */
        public StringPrimitive|string|null $display = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

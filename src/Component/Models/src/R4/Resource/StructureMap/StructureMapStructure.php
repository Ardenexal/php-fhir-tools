<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapModelModeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A structure definition used by this map. The structure definition may describe instances that are converted, or the instances that are produced.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.structure', fhirVersion: 'R4')]
class StructureMapStructure extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CanonicalPrimitive|null url Canonical reference to structure definition */
        #[NotBlank]
        public ?CanonicalPrimitive $url = null,
        /** @var StructureMapModelModeType|null mode source | queried | target | produced */
        #[NotBlank]
        public ?StructureMapModelModeType $mode = null,
        /** @var StringPrimitive|string|null alias Name for type in this map */
        public StringPrimitive|string|null $alias = null,
        /** @var StringPrimitive|string|null documentation Documentation on use of structure */
        public StringPrimitive|string|null $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

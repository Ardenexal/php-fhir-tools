<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapGroupTypeModeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Organizes the mapping into manageable chunks for human review/ease of maintenance.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group', fhirVersion: 'R4')]
class StructureMapGroup extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IdPrimitive|null name Human-readable label */
        #[NotBlank]
        public ?IdPrimitive $name = null,
        /** @var IdPrimitive|null extends Another group that this group adds rules to */
        public ?IdPrimitive $extends = null,
        /** @var StructureMapGroupTypeModeType|null typeMode none | types | type-and-types */
        #[NotBlank]
        public ?StructureMapGroupTypeModeType $typeMode = null,
        /** @var StringPrimitive|string|null documentation Additional description/explanation for group */
        public StringPrimitive|string|null $documentation = null,
        /** @var array<StructureMapGroupInput> input Named instance provided when invoking the map */
        public array $input = [],
        /** @var array<StructureMapGroupRule> rule Transform Rule from source to target */
        public array $rule = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Organizes the mapping into manageable chunks for human review/ease of maintenance.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group', fhirVersion: 'R4')]
class FHIRStructureMapGroup extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null name Human-readable label */
        #[NotBlank]
        public ?FHIRId $name = null,
        /** @var FHIRId|null extends Another group that this group adds rules to */
        public ?FHIRId $extends = null,
        /** @var FHIRStructureMapGroupTypeModeType|null typeMode none | types | type-and-types */
        #[NotBlank]
        public ?FHIRStructureMapGroupTypeModeType $typeMode = null,
        /** @var FHIRString|string|null documentation Additional description/explanation for group */
        public FHIRString|string|null $documentation = null,
        /** @var array<FHIRStructureMapGroupInput> input Named instance provided when invoking the map */
        public array $input = [],
        /** @var array<FHIRStructureMapGroupRule> rule Transform Rule from source to target */
        public array $rule = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

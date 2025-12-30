<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRStructureMapTargetListModeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRStructureMapTransformType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Content to create because of this mapping rule.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.target', fhirVersion: 'R5')]
class FHIRStructureMapGroupRuleTarget extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null context Variable this rule applies to */
        public FHIRString|string|null $context = null,
        /** @var FHIRString|string|null element Field to create in the context */
        public FHIRString|string|null $element = null,
        /** @var FHIRId|null variable Named context for field, if desired, and a field is specified */
        public ?FHIRId $variable = null,
        /** @var array<FHIRStructureMapTargetListModeType> listMode first | share | last | single */
        public array $listMode = [],
        /** @var FHIRId|null listRuleId Internal rule reference for shared list items */
        public ?FHIRId $listRuleId = null,
        /** @var FHIRStructureMapTransformType|null transform create | copy + */
        public ?FHIRStructureMapTransformType $transform = null,
        /** @var array<FHIRStructureMapGroupRuleTargetParameter> parameter Parameters to the transform */
        public array $parameter = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

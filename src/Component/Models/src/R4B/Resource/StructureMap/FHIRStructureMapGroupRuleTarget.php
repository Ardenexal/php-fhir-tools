<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Content to create because of this mapping rule.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.target', fhirVersion: 'R4B')]
class FHIRStructureMapGroupRuleTarget extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null context Type or variable this rule applies to */
        public ?\FHIRId $context = null,
        /** @var FHIRStructureMapContextTypeType|null contextType type | variable */
        public ?\FHIRStructureMapContextTypeType $contextType = null,
        /** @var FHIRString|string|null element Field to create in the context */
        public \FHIRString|string|null $element = null,
        /** @var FHIRId|null variable Named context for field, if desired, and a field is specified */
        public ?\FHIRId $variable = null,
        /** @var array<FHIRStructureMapTargetListModeType> listMode first | share | last | collate */
        public array $listMode = [],
        /** @var FHIRId|null listRuleId Internal rule reference for shared list items */
        public ?\FHIRId $listRuleId = null,
        /** @var FHIRStructureMapTransformType|null transform create | copy + */
        public ?\FHIRStructureMapTransformType $transform = null,
        /** @var array<FHIRStructureMapGroupRuleTargetParameter> parameter Parameters to the transform */
        public array $parameter = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

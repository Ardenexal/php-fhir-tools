<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Transform Rule from source to target.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule', fhirVersion: 'R4')]
class FHIRStructureMapGroupRule extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null name Name of the rule for internal references */
        #[NotBlank]
        public ?FHIRId $name = null,
        /** @var array<FHIRStructureMapGroupRuleSource> source Source inputs to the mapping */
        public array $source = [],
        /** @var array<FHIRStructureMapGroupRuleTarget> target Content to create because of this mapping rule */
        public array $target = [],
        /** @var array<FHIRStructureMapGroupRule> rule Rules contained in this rule */
        public array $rule = [],
        /** @var array<FHIRStructureMapGroupRuleDependent> dependent Which other rules to apply in the context of this rule */
        public array $dependent = [],
        /** @var FHIRString|string|null documentation Documentation for this instance of data */
        public FHIRString|string|null $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

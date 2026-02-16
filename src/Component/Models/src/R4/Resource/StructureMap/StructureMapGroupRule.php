<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Transform Rule from source to target.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule', fhirVersion: 'R4')]
class StructureMapGroupRule extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IdPrimitive|null name Name of the rule for internal references */
        #[NotBlank]
        public ?IdPrimitive $name = null,
        /** @var array<StructureMapGroupRuleSource> source Source inputs to the mapping */
        public array $source = [],
        /** @var array<StructureMapGroupRuleTarget> target Content to create because of this mapping rule */
        public array $target = [],
        /** @var array<StructureMapGroupRule> rule Rules contained in this rule */
        public array $rule = [],
        /** @var array<StructureMapGroupRuleDependent> dependent Which other rules to apply in the context of this rule */
        public array $dependent = [],
        /** @var StringPrimitive|string|null documentation Documentation for this instance of data */
        public StringPrimitive|string|null $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

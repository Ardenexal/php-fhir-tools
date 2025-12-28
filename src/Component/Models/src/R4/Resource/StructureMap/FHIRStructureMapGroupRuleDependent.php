<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Which other rules to apply in the context of this rule.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.dependent', fhirVersion: 'R4')]
class FHIRStructureMapGroupRuleDependent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null name Name of a rule or group to apply */
        #[NotBlank]
        public ?FHIRId $name = null,
        /** @var array<FHIRString|string> variable Variable to pass to the rule or group */
        public array $variable = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

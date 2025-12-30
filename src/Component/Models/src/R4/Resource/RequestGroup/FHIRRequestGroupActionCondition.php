<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRActionConditionKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An expression that describes applicability criteria, or start/stop conditions for the action.
 */
#[FHIRBackboneElement(parentResource: 'RequestGroup', elementPath: 'RequestGroup.action.condition', fhirVersion: 'R4')]
class FHIRRequestGroupActionCondition extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRActionConditionKindType|null kind applicability | start | stop */
        #[NotBlank]
        public ?FHIRActionConditionKindType $kind = null,
        /** @var FHIRExpression|null expression Boolean-valued expression */
        public ?FHIRExpression $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

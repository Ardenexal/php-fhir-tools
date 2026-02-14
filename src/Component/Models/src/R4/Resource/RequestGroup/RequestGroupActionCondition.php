<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ActionConditionKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An expression that describes applicability criteria, or start/stop conditions for the action.
 */
#[FHIRBackboneElement(parentResource: 'RequestGroup', elementPath: 'RequestGroup.action.condition', fhirVersion: 'R4')]
class RequestGroupActionCondition extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ActionConditionKindType|null kind applicability | start | stop */
        #[NotBlank]
        public ?ActionConditionKindType $kind = null,
        /** @var Expression|null expression Boolean-valued expression */
        public ?Expression $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

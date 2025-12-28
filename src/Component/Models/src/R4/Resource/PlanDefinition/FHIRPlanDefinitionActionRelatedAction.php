<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A relationship to another action such as "before" or "30-60 minutes after start of".
 */
#[FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.relatedAction', fhirVersion: 'R4')]
class FHIRPlanDefinitionActionRelatedAction extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null actionId What action is this related to */
        #[NotBlank]
        public ?FHIRId $actionId = null,
        /** @var FHIRActionRelationshipTypeType|null relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
        #[NotBlank]
        public ?FHIRActionRelationshipTypeType $relationship = null,
        /** @var FHIRDuration|FHIRRange|null offsetX Time offset for the relationship */
        public FHIRDuration|FHIRRange|null $offsetX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

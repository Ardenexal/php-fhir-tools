<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A relationship to another action such as "before" or "30-60 minutes after start of".
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.relatedAction', fhirVersion: 'R5')]
class FHIRPlanDefinitionActionRelatedAction extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null targetId What action is this related to */
        #[NotBlank]
        public ?FHIRId $targetId = null,
        /** @var FHIRActionRelationshipTypeType|null relationship before | before-start | before-end | concurrent | concurrent-with-start | concurrent-with-end | after | after-start | after-end */
        #[NotBlank]
        public ?FHIRActionRelationshipTypeType $relationship = null,
        /** @var FHIRActionRelationshipTypeType|null endRelationship before | before-start | before-end | concurrent | concurrent-with-start | concurrent-with-end | after | after-start | after-end */
        public ?FHIRActionRelationshipTypeType $endRelationship = null,
        /** @var FHIRDuration|FHIRRange|null offsetX Time offset for the relationship */
        public FHIRDuration|FHIRRange|null $offsetX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

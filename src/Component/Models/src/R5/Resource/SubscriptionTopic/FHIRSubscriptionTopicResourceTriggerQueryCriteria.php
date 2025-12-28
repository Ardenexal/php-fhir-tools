<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The FHIR query based rules that the server should use to determine when to trigger a notification for this subscription topic.
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.resourceTrigger.queryCriteria', fhirVersion: 'R5')]
class FHIRSubscriptionTopicResourceTriggerQueryCriteria extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null previous Rule applied to previous resource state */
        public \FHIRString|string|null $previous = null,
        /** @var FHIRCriteriaNotExistsBehaviorType|null resultForCreate test-passes | test-fails */
        public ?\FHIRCriteriaNotExistsBehaviorType $resultForCreate = null,
        /** @var FHIRString|string|null current Rule applied to current resource state */
        public \FHIRString|string|null $current = null,
        /** @var FHIRCriteriaNotExistsBehaviorType|null resultForDelete test-passes | test-fails */
        public ?\FHIRCriteriaNotExistsBehaviorType $resultForDelete = null,
        /** @var FHIRBoolean|null requireBoth Both must be true flag */
        public ?\FHIRBoolean $requireBoth = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

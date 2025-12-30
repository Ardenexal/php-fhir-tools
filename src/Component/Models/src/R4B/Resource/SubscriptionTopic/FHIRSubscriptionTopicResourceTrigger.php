<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRInteractionTriggerType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A definition of a resource-based event that triggers a notification based on the SubscriptionTopic. The criteria may be just a human readable description and/or a full FHIR search string or FHIRPath expression. Multiple triggers are considered OR joined (e.g., a resource update matching ANY of the definitions will trigger a notification).
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.resourceTrigger', fhirVersion: 'R4B')]
class FHIRSubscriptionTopicResourceTrigger extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description Text representation of the resource trigger */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRUri|null resource Data Type or Resource (reference to definition) for this trigger definition */
        #[NotBlank]
        public ?FHIRUri $resource = null,
        /** @var array<FHIRInteractionTriggerType> supportedInteraction create | update | delete */
        public array $supportedInteraction = [],
        /** @var FHIRSubscriptionTopicResourceTriggerQueryCriteria|null queryCriteria Query based trigger rule */
        public ?FHIRSubscriptionTopicResourceTriggerQueryCriteria $queryCriteria = null,
        /** @var FHIRString|string|null fhirPathCriteria FHIRPath based trigger rule */
        public FHIRString|string|null $fhirPathCriteria = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

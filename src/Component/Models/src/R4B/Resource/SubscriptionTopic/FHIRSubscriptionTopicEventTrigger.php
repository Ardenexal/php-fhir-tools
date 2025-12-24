<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Event definition which can be used to trigger the SubscriptionTopic.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.eventTrigger', fhirVersion: 'R4B')]
class FHIRSubscriptionTopicEventTrigger extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description Text representation of the event trigger */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRCodeableConcept|null event Event which can trigger a notification from the SubscriptionTopic */
        #[NotBlank]
        public ?FHIRCodeableConcept $event = null,
        /** @var FHIRUri|null resource Data Type or Resource (reference to definition) for this trigger definition */
        #[NotBlank]
        public ?FHIRUri $resource = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

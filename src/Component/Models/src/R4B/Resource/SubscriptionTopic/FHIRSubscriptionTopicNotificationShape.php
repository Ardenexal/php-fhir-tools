<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of properties to describe the shape (e.g., resources) included in notifications from this Subscription Topic.
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.notificationShape', fhirVersion: 'R4B')]
class FHIRSubscriptionTopicNotificationShape extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null resource URL of the Resource that is the focus (main) resource in a notification shape */
        #[NotBlank]
        public ?\FHIRUri $resource = null,
        /** @var array<FHIRString|string> include Include directives, rooted in the resource for this shape */
        public array $include = [],
        /** @var array<FHIRString|string> revInclude Reverse include directives, rooted in the resource for this shape */
        public array $revInclude = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

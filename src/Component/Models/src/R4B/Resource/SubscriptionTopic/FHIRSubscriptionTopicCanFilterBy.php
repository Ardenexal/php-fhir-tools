<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSubscriptionSearchModifierType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of properties by which Subscriptions on the SubscriptionTopic can be filtered. May be defined Search Parameters (e.g., Encounter.patient) or parameters defined within this SubscriptionTopic context (e.g., hub.event).
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.canFilterBy', fhirVersion: 'R4B')]
class FHIRSubscriptionTopicCanFilterBy extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description Description of this filter parameter */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRUri|null resource URL of the triggering Resource that this filter applies to */
        public ?FHIRUri $resource = null,
        /** @var FHIRString|string|null filterParameter Human-readable and computation-friendly name for a filter parameter usable by subscriptions on this topic, via Subscription.filterBy.filterParameter */
        #[NotBlank]
        public FHIRString|string|null $filterParameter = null,
        /** @var FHIRUri|null filterDefinition Canonical URL for a filterParameter definition */
        public ?FHIRUri $filterDefinition = null,
        /** @var array<FHIRSubscriptionSearchModifierType> modifier = | eq | ne | gt | lt | ge | le | sa | eb | ap | above | below | in | not-in | of-type */
        public array $modifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

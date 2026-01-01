<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchComparatorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchModifierCodeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of properties by which Subscriptions on the SubscriptionTopic can be filtered. May be defined Search Parameters (e.g., Encounter.patient) or parameters defined within this SubscriptionTopic context (e.g., hub.event).
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.canFilterBy', fhirVersion: 'R5')]
class FHIRSubscriptionTopicCanFilterBy extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        /** @var array<FHIRSearchComparatorType> comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
        public array $comparator = [],
        /** @var array<FHIRSearchModifierCodeType> modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | of-type | code-text | text-advanced | iterate */
        public array $modifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

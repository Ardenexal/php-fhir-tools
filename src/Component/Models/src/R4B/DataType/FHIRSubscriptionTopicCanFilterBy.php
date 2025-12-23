<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubscriptionTopic.canFilterBy
 * @description List of properties by which Subscriptions on the SubscriptionTopic can be filtered. May be defined Search Parameters (e.g., Encounter.patient) or parameters defined within this SubscriptionTopic context (e.g., hub.event).
 */
class FHIRSubscriptionTopicCanFilterBy extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description Description of this filter parameter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri resource URL of the triggering Resource that this filter applies to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string filterParameter Human-readable and computation-friendly name for a filter parameter usable by subscriptions on this topic, via Subscription.filterBy.filterParameter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $filterParameter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri filterDefinition Canonical URL for a filterParameter definition */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $filterDefinition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubscriptionSearchModifierType> modifier = | eq | ne | gt | lt | ge | le | sa | eb | ap | above | below | in | not-in | of-type */
		public array $modifier = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

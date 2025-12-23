<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubscriptionTopic.resourceTrigger
 * @description A definition of a resource-based event that triggers a notification based on the SubscriptionTopic. The criteria may be just a human readable description and/or a full FHIR search string or FHIRPath expression. Multiple triggers are considered OR joined (e.g., a resource update matching ANY of the definitions will trigger a notification).
 */
class FHIRSubscriptionTopicResourceTrigger extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description Text representation of the resource trigger */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri resource Data Type or Resource (reference to definition) for this trigger definition */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $resource = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteractionTriggerType> supportedInteraction create | update | delete */
		public array $supportedInteraction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubscriptionTopicResourceTriggerQueryCriteria queryCriteria Query based trigger rule */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubscriptionTopicResourceTriggerQueryCriteria $queryCriteria = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string fhirPathCriteria FHIRPath based trigger rule */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $fhirPathCriteria = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

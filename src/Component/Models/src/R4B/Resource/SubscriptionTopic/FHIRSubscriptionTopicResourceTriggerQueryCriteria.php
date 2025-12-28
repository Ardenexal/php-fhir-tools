<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description The FHIR query based rules that the server should use to determine when to trigger a notification for this subscription topic.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubscriptionTopic', elementPath: 'SubscriptionTopic.resourceTrigger.queryCriteria', fhirVersion: 'R4B')]
class FHIRSubscriptionTopicResourceTriggerQueryCriteria extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string previous Rule applied to previous resource state */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $previous = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCriteriaNotExistsBehaviorType resultForCreate test-passes | test-fails */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCriteriaNotExistsBehaviorType $resultForCreate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string current Rule applied to current resource state */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $current = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCriteriaNotExistsBehaviorType resultForDelete test-passes | test-fails */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCriteriaNotExistsBehaviorType $resultForDelete = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean requireBoth Both must be true flag */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $requireBoth = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubscriptionStatus.notificationEvent
 * @description Detailed information about events relevant to this subscription notification.
 */
class FHIRSubscriptionStatusNotificationEvent extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger64 eventNumber Sequencing index of this event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger64 $eventNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant timestamp The instant this event occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant $timestamp = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference focus Reference to the primary resource or information of this event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $focus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> additionalContext References related to the focus resource and/or context of this event */
		public array $additionalContext = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

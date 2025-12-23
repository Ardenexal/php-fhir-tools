<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubscriptionStatus.notificationEvent
 * @description Detailed information about events relevant to this subscription notification.
 */
class FHIRSubscriptionStatusNotificationEvent extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string eventNumber Event number */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $eventNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant timestamp The instant this event occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant $timestamp = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference focus The focus of this event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $focus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> additionalContext Additional context for this event */
		public array $additionalContext = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Subscription.channel
 * @description Details where to send notifications when resources are received that meet the criteria.
 */
class FHIRSubscriptionChannel extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubscriptionChannelTypeType type rest-hook | websocket | email | sms | message */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubscriptionChannelTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUrl endpoint Where the channel points to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUrl $endpoint = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType payload MIME type to send, or omit for no payload */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType $payload = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string> header Usage depends on the channel type */
		public array $header = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

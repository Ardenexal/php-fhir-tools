<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Details where to send notifications when resources are received that meet the criteria.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Subscription', elementPath: 'Subscription.channel', fhirVersion: 'R4')]
class FHIRSubscriptionChannel extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSubscriptionChannelTypeType type rest-hook | websocket | email | sms | message */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSubscriptionChannelTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUrl endpoint Where the channel points to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUrl $endpoint = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMimeTypesType payload MIME type to send, or omit for no payload */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMimeTypesType $payload = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string> header Usage depends on the channel type */
		public array $header = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Details where to send notifications when resources are received that meet the criteria.
 */
#[FHIRBackboneElement(parentResource: 'Subscription', elementPath: 'Subscription.channel', fhirVersion: 'R4B')]
class FHIRSubscriptionChannel extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRSubscriptionChannelTypeType|null type rest-hook | websocket | email | sms | message */
        #[NotBlank]
        public ?\FHIRSubscriptionChannelTypeType $type = null,
        /** @var FHIRUrl|null endpoint Where the channel points to */
        public ?\FHIRUrl $endpoint = null,
        /** @var FHIRMimeTypesType|null payload MIME type to send, or omit for no payload */
        public ?\FHIRMimeTypesType $payload = null,
        /** @var array<FHIRString|string> header Usage depends on the channel type */
        public array $header = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

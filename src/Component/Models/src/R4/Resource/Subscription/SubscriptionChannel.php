<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Subscription;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SubscriptionChannelTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Details where to send notifications when resources are received that meet the criteria.
 */
#[FHIRBackboneElement(parentResource: 'Subscription', elementPath: 'Subscription.channel', fhirVersion: 'R4')]
class SubscriptionChannel extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var SubscriptionChannelTypeType|null type rest-hook | websocket | email | sms | message */
        #[NotBlank]
        public ?SubscriptionChannelTypeType $type = null,
        /** @var UrlPrimitive|null endpoint Where the channel points to */
        public ?UrlPrimitive $endpoint = null,
        /** @var MimeTypesType|null payload MIME type to send, or omit for no payload */
        public ?MimeTypesType $payload = null,
        /** @var array<StringPrimitive|string> header Usage depends on the channel type */
        public array $header = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

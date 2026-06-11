<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Subscription;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\SubscriptionChannelTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Details where to send notifications when resources are received that meet the criteria.
 */
#[FHIRBackboneElement(parentResource: 'Subscription', elementPath: 'Subscription.channel', fhirVersion: 'R4B')]
class SubscriptionChannel extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var SubscriptionChannelTypeType|null type rest-hook | websocket | email | sms | message */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/subscription-channel-type|4.3.0', strength: 'required')]
        public ?SubscriptionChannelTypeType $type = null,
        /** @var UrlPrimitive|null endpoint Where the channel points to */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?UrlPrimitive $endpoint = null,
        /** @var MimeTypesType|null payload MIME type to send, or omit for no payload */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/mimetypes|4.3.0', strength: 'required')]
        public ?MimeTypesType $payload = null,
        /** @var array<StringPrimitive|string> header Usage depends on the channel type */
        #[FhirProperty(
            fhirType: 'string',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
        )]
        public array $header = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

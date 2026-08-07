<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SubscriptionEvents;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'events',
    url: 'http://hl7.org/fhir/OperationDefinition/Subscription-events',
    version: 'R5',
    inputClass: SubscriptionEventsInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Subscription'],
    instance: true,
    type: false,
    system: false,
)]
final class SubscriptionEventsOperation
{
}

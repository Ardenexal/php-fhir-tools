<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SubscriptionStatus;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'status',
    url: 'http://hl7.org/fhir/OperationDefinition/Subscription-status',
    version: 'R5',
    inputClass: SubscriptionStatusInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Subscription'],
    instance: true,
    type: true,
    system: false,
)]
final class SubscriptionStatusOperation
{
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SubscriptionGetWsBindingToken;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'get-ws-binding-token',
    url: 'http://hl7.org/fhir/OperationDefinition/Subscription-get-ws-binding-token',
    version: 'R5',
    inputClass: SubscriptionGetWsBindingTokenInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: SubscriptionGetWsBindingTokenOutput::class,
    resource: ['Subscription'],
    instance: true,
    type: true,
    system: false,
)]
final class SubscriptionGetWsBindingTokenOperation
{
}

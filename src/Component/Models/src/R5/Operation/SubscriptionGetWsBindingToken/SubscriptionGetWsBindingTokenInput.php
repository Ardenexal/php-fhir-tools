<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SubscriptionGetWsBindingToken;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Subscription-get-ws-binding-token',
    use: 'in',
    version: 'R5',
    operation: 'SubscriptionGetWsBindingToken',
    path: '',
)]
final class SubscriptionGetWsBindingTokenInput
{
    /**
     * @param list<string> $id
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'id',
            phpName: 'id',
            use: 'in',
            min: 0,
            max: '*',
            type: 'id',
            documentation: 'At the Instance level, this parameter is ignored. At the Resource level, one or more parameters containing a FHIR id for a Subscription to get a token for. In the absence of any specified ids, the server may either return a token for all Subscriptions available to the caller with a channel-type of websocket or fail the request.',
            scope: ['type'],
        )]
        public readonly array $id = [],
    ) {
    }
}

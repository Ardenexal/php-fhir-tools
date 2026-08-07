<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SubscriptionGetWsBindingToken;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Subscription-get-ws-binding-token',
    use: 'out',
    version: 'R5',
    operation: 'SubscriptionGetWsBindingToken',
    path: '',
)]
final class SubscriptionGetWsBindingTokenOutput
{
    /**
     * @param list<string> $subscription
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'token',
            phpName: 'token',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'An access token that a client may use to show authorization during a websocket connection. The security details of the token are implementation-dependent and beyond the scope of this operation definition.',
        )]
        public readonly ?string $token = null,
        #[FhirOperationParameter(
            name: 'expiration',
            phpName: 'expiration',
            use: 'out',
            min: 1,
            max: '1',
            type: 'dateTime',
            documentation: 'The date and time this token is valid until.',
        )]
        public readonly ?string $expiration = null,
        #[FhirOperationParameter(
            name: 'subscription',
            phpName: 'subscription',
            use: 'out',
            min: 0,
            max: '*',
            type: 'string',
            documentation: 'The subscriptions this token is valid for.',
        )]
        public readonly array $subscription = [],
        #[FhirOperationParameter(
            name: 'websocket-url',
            phpName: 'websocketUrl',
            use: 'out',
            min: 1,
            max: '1',
            type: 'url',
            documentation: 'The URL the client should use to connect to Websockets.',
        )]
        public readonly ?string $websocketUrl = null,
    ) {
    }
}

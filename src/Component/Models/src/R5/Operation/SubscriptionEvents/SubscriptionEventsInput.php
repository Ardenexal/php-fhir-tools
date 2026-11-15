<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SubscriptionEvents;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Subscription-events',
    use: 'in',
    version: 'R5',
    operation: 'SubscriptionEvents',
    path: '',
)]
final class SubscriptionEventsInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'eventsSinceNumber',
            phpName: 'eventsSinceNumber',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer64',
            documentation: 'The starting event number, inclusive of this event (lower bound).',
        )]
        public readonly ?int $eventsSinceNumber = null,
        #[FhirOperationParameter(
            name: 'eventsUntilNumber',
            phpName: 'eventsUntilNumber',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer64',
            documentation: 'The ending event number, inclusive of this event (upper bound).',
        )]
        public readonly ?int $eventsUntilNumber = null,
        #[FhirOperationParameter(
            name: 'content',
            phpName: 'content',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'Requested content style of returned data. Codes from backport-content-value-set (e.g., empty, id-only, full-resource). This is a hint to the server what a client would prefer, and MAY be ignored.',
        )]
        public readonly ?string $content = null,
    ) {
    }
}

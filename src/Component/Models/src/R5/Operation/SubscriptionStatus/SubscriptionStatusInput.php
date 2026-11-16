<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\SubscriptionStatus;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Subscription-status',
    use: 'in',
    version: 'R5',
    operation: 'SubscriptionStatus',
    path: '',
)]
final class SubscriptionStatusInput
{
    /**
     * @param list<string> $id
     * @param list<string> $status
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'id',
            phpName: 'id',
            use: 'in',
            min: 0,
            max: '*',
            type: 'id',
            documentation: 'At the Instance level, this parameter is ignored. At the Resource level, one or more FHIR ids to Subscription resources to get status information for. In the absence of any specified ids, the server returns the status for all Subscriptions available to the caller. Multiple values are joined via OR (e.g., "id1" OR "id2").',
            scope: ['type'],
        )]
        public readonly array $id = [],
        #[FhirOperationParameter(
            name: 'status',
            phpName: 'status',
            use: 'in',
            min: 0,
            max: '*',
            type: 'code',
            documentation: 'At the Instance level, this parameter is ignored. At the Resource level, a Subscription status code to filter by (e.g., "active"). In the absence of any specified status values, the server does not filter contents based on the status. Multiple values are joined via OR (e.g., "error" OR "off").',
            scope: ['type'],
        )]
        public readonly array $status = [],
    ) {
    }
}

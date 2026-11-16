<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceRemove;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-remove',
    use: 'in',
    version: 'R5',
    operation: 'ResourceRemove',
    path: '',
)]
final class ResourceRemoveInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'removals',
            phpName: 'removals',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'Resource containing content to remove. See [Operations for Large Resources](operations-for-large-resources.html).',
        )]
        public readonly ?AbstractResource $removals = null,
    ) {
    }
}

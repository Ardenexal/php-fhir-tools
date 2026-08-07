<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceGraphql;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-graphql',
    use: 'in',
    version: 'R5',
    operation: 'ResourceGraphql',
    path: '',
)]
final class ResourceGraphqlInput
{
    public function __construct(
        #[FhirOperationParameter(name: 'query', phpName: 'query', use: 'in', min: 1, max: '1', type: 'string')]
        public readonly ?string $query = null,
    ) {
    }
}

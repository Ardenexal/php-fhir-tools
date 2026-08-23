<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceFilter;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-filter',
    use: 'in',
    version: 'R5',
    operation: 'ResourceFilter',
    path: '',
)]
final class ResourceFilterInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'probes',
            phpName: 'probes',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'Resource containing content that acts as a filter. See [Operations for Large Resources](operations-for-large-resources.html).',
        )]
        public readonly ?AbstractResource $probes = null,
    ) {
    }
}

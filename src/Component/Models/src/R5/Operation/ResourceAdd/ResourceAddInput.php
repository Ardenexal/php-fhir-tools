<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceAdd;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-add',
    use: 'in',
    version: 'R5',
    operation: 'ResourceAdd',
    path: '',
)]
final class ResourceAddInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'additions',
            phpName: 'additions',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'Resource containing content to add. See [Operations for Large Resources](operations-for-large-resources.html).',
        )]
        public readonly ?AbstractResource $additions = null,
    ) {
    }
}

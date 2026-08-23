<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ResourceMetaDelete;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-meta-delete',
    use: 'out',
    version: 'R4',
    operation: 'ResourceMetaDelete',
    path: '',
)]
final class ResourceMetaDeleteOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'return',
            phpName: 'return',
            use: 'out',
            min: 1,
            max: '1',
            type: 'Meta',
            documentation: 'Resulting meta for the resource',
        )]
        public readonly ?Meta $return = null,
    ) {
    }
}

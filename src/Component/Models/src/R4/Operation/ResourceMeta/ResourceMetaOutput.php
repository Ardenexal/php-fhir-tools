<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ResourceMeta;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-meta',
    use: 'out',
    version: 'R4',
    operation: 'ResourceMeta',
    path: '',
)]
final class ResourceMetaOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'return',
            phpName: 'return',
            use: 'out',
            min: 1,
            max: '1',
            type: 'Meta',
            documentation: 'The meta returned by the operation',
        )]
        public readonly ?Meta $return = null,
    ) {
    }
}

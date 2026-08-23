<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ResourceConvert;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-convert',
    use: 'in',
    version: 'R4B',
    operation: 'ResourceConvert',
    path: '',
)]
final class ResourceConvertInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'input',
            phpName: 'input',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Resource',
            documentation: 'The resource that is to be converted',
        )]
        public readonly ?AbstractResource $input = null,
    ) {
    }
}

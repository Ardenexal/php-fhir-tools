<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ResourceMetaAdd;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-meta-add',
    use: 'in',
    version: 'R4',
    operation: 'ResourceMetaAdd',
    path: '',
)]
final class ResourceMetaAddInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'meta',
            phpName: 'meta',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Meta',
            documentation: 'Profiles, tags, and security labels to add to the existing resource. Note that profiles, tags, and security labels are sets, and duplicates are not created.  The identity of a tag or security label is the system+code. When matching existing tags during adding, version and display are ignored. For profiles, matching is based on the full URL',
        )]
        public readonly ?Meta $meta = null,
    ) {
    }
}

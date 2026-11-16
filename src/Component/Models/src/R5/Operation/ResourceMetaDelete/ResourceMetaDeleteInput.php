<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceMetaDelete;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-meta-delete',
    use: 'in',
    version: 'R5',
    operation: 'ResourceMetaDelete',
    path: '',
)]
final class ResourceMetaDeleteInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'meta',
            phpName: 'meta',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Meta',
            documentation: 'Profiles, tags, and security labels to delete from the existing resource. It is not an error if these tags, profiles, and labels do not exist.  The identity of a tag or security label is the system+code. When matching existing tags during deletion, version and display are ignored. For profiles, matching is based on the full URL',
        )]
        public readonly ?Meta $meta = null,
    ) {
    }
}

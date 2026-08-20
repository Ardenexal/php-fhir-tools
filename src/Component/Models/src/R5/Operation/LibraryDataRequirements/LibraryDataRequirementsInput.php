<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\LibraryDataRequirements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Library-data-requirements',
    use: 'in',
    version: 'R5',
    operation: 'LibraryDataRequirements',
    path: '',
)]
final class LibraryDataRequirementsInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'target',
            phpName: 'target',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The target of the data requirements operation',
        )]
        public readonly ?string $target = null,
    ) {
    }
}

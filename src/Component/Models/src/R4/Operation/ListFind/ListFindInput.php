<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ListFind;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/List-find',
    use: 'in',
    version: 'R4',
    operation: 'ListFind',
    path: '',
)]
final class ListFindInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'patient',
            phpName: 'patient',
            use: 'in',
            min: 1,
            max: '1',
            type: 'id',
            documentation: 'The id of a patient resource located on the server on which this operation is executed',
        )]
        public readonly ?string $patient = null,
        #[FhirOperationParameter(
            name: 'name',
            phpName: 'name',
            use: 'in',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'The code for the functional list that is being found',
        )]
        public readonly ?string $name = null,
    ) {
    }
}

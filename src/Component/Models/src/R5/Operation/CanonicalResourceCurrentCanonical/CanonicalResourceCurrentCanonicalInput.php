<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CanonicalResourceCurrentCanonical;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CanonicalResource-current-canonical',
    use: 'in',
    version: 'R5',
    operation: 'CanonicalResourceCurrentCanonical',
    path: '',
)]
final class CanonicalResourceCurrentCanonicalInput
{
    /**
     * @param list<string> $status
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 1,
            max: '1',
            type: 'uri',
            documentation: 'This is the canonical URL (with no version declared)',
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'status',
            phpName: 'status',
            use: 'in',
            min: 0,
            max: '*',
            type: 'code',
            documentation: 'The statuses to allow to be returned. If no status codes are provided, then any status is ok',
        )]
        public readonly array $status = [],
    ) {
    }
}

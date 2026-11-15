<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\DocumentReferenceGenerate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/DocumentReference-generate',
    use: 'in',
    version: 'R5',
    operation: 'DocumentReferenceGenerate',
    path: '',
)]
final class DocumentReferenceGenerateInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 1,
            max: '1',
            type: 'uri',
            documentation: 'The URL to the source document. This may be a general URL or a Binary or a Composition or a Bundle.',
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'persist',
            phpName: 'persist',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Whether to store the document at the document end-point (/Document) or not, once it is generated (default is for the server to decide).',
        )]
        public readonly ?bool $persist = null,
    ) {
    }
}

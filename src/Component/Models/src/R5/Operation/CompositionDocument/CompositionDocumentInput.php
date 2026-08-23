<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\CompositionDocument;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Composition-document',
    use: 'in',
    version: 'R5',
    operation: 'CompositionDocument',
    path: '',
)]
final class CompositionDocumentInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'persist',
            phpName: 'persist',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Whether to store the document at the bundle end-point (/Bundle) or not once it is generated. Value = true or false (default is for the server to decide). If the document is stored, its location can be inferred from the Bundle.id, but it SHOULD be provided explicitly in the HTTP Location header in the response',
        )]
        public readonly ?bool $persist = null,
        #[FhirOperationParameter(
            name: 'graph',
            phpName: 'graph',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'Canonical reference to a GraphDefinition. If a URL is provided, it is the canonical reference to a [GraphDefinition](graphdefinition.html) that it controls what resources are to be added to the bundle when building the document. The GraphDefinition can also specify profiles that apply to the various resources',
        )]
        public readonly ?string $graph = null,
    ) {
    }
}

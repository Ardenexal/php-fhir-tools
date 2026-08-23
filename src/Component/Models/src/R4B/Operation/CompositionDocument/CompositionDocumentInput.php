<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CompositionDocument;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Composition-document',
    use: 'in',
    version: 'R4B',
    operation: 'CompositionDocument',
    path: '',
)]
final class CompositionDocumentInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'id',
            phpName: 'id',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: "Identifies the composition to use. This can either be a simple id, which identifies a composition, or it can be a full URL, which identifies a composition on another server. \n\nNotes: \n\n* GET [base]/Composition/[id]/\$document is identical in meaning to GET [base]/Composition/\$document?id=[id]\n* the id parameter SHALL NOT be used if the operation is requested on a particular composition (e.g.  GET [base]/Composition/[id]/\$document?id=[id] is not allowed)\n* Servers are not required to support generating documents on Compositions located on another server",
        )]
        public readonly ?string $id = null,
        #[FhirOperationParameter(
            name: 'persist',
            phpName: 'persist',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Whether to store the document at the bundle end-point (/Bundle) or not once it is generated. Value = true or false (default is for the server to decide). If the document is stored, it\'s location can be inferred from the Bundle.id, but it SHOULD be provided explicitly in the HTTP Location header in the response',
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

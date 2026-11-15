<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\QuestionnairePopulate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

#[FhirOperationPayload(
    operationUrl: 'http://h7.org/fhir/OperationDefinition/example',
    use: 'in',
    version: 'R4',
    operation: 'QuestionnairePopulate',
    path: '',
)]
final class QuestionnairePopulateInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'subject',
            phpName: 'subject',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Reference',
            documentation: 'The resource that is to be the *QuestionnaireResponse.subject*. The [[[QuestionnaireResponse]]]      instance will reference the provided subject.  In addition, if the *local* parameter is      set to true, server information about the specified subject will be used to populate the      instance.',
        )]
        public readonly ?Reference $subject = null,
        #[FhirOperationParameter(
            name: 'local',
            phpName: 'local',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Reference',
            documentation: 'If the *local* parameter is set to true, server information about the specified subject will be used to populate the instance.',
        )]
        public readonly ?Reference $local = null,
    ) {
    }
}

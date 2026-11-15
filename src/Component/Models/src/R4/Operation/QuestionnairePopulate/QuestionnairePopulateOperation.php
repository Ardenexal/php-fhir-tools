<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\QuestionnairePopulate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;

#[FhirOperation(
    code: 'populate',
    url: 'http://h7.org/fhir/OperationDefinition/example',
    version: 'R4',
    inputClass: QuestionnairePopulateInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: QuestionnaireResponseResource::class,
    resource: ['Questionnaire'],
    instance: true,
    type: false,
    system: false,
)]
final class QuestionnairePopulateOperation
{
}

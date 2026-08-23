<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\StructureDefinitionQuestionnaire;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;

#[FhirOperation(
    code: 'questionnaire',
    url: 'http://hl7.org/fhir/OperationDefinition/StructureDefinition-questionnaire',
    version: 'R4',
    inputClass: StructureDefinitionQuestionnaireInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: QuestionnaireResource::class,
    resource: ['StructureDefinition'],
    instance: true,
    type: true,
    system: false,
)]
final class StructureDefinitionQuestionnaireOperation
{
}

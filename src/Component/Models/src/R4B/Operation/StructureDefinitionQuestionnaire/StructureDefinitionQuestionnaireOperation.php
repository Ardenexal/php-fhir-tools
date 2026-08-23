<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\StructureDefinitionQuestionnaire;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResource;

#[FhirOperation(
    code: 'questionnaire',
    url: 'http://hl7.org/fhir/OperationDefinition/StructureDefinition-questionnaire',
    version: 'R4B',
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

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\MeasureEvaluateMeasure;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReportResource;

#[FhirOperation(
    code: 'evaluate-measure',
    url: 'http://hl7.org/fhir/OperationDefinition/Measure-evaluate-measure',
    version: 'R4',
    inputClass: MeasureEvaluateMeasureInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: MeasureReportResource::class,
    resource: ['Measure'],
    instance: true,
    type: true,
    system: false,
)]
final class MeasureEvaluateMeasureOperation
{
}

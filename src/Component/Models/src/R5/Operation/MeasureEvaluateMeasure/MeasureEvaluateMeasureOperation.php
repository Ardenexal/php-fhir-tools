<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MeasureEvaluateMeasure;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

#[FhirOperation(
    code: 'evaluate-measure',
    url: 'http://hl7.org/fhir/OperationDefinition/Measure-evaluate-measure',
    version: 'R5',
    inputClass: MeasureEvaluateMeasureInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Measure'],
    instance: true,
    type: true,
    system: false,
)]
final class MeasureEvaluateMeasureOperation
{
}

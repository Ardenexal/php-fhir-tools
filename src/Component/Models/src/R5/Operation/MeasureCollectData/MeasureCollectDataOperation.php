<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\MeasureCollectData;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

#[FhirOperation(
    code: 'collect-data',
    url: 'http://hl7.org/fhir/OperationDefinition/Measure-collect-data',
    version: 'R5',
    inputClass: MeasureCollectDataInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: MeasureCollectDataOutput::class,
    resource: ['Measure'],
    instance: true,
    type: true,
    system: false,
)]
final class MeasureCollectDataOperation
{
}

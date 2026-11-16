<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\MeasureDataRequirements;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\LibraryResource;

#[FhirOperation(
    code: 'data-requirements',
    url: 'http://hl7.org/fhir/OperationDefinition/Measure-data-requirements',
    version: 'R4',
    inputClass: MeasureDataRequirementsInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: LibraryResource::class,
    resource: ['Measure'],
    instance: true,
    type: false,
    system: false,
)]
final class MeasureDataRequirementsOperation
{
}

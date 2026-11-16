<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\MedicinalProductDefinitionEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BundleResource;

#[FhirOperation(
    code: 'everything',
    url: 'http://hl7.org/fhir/OperationDefinition/MedicinalProductDefinition-everything',
    version: 'R4B',
    inputClass: MedicinalProductDefinitionEverythingInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['MedicinalProductDefinition'],
    instance: true,
    type: true,
    system: false,
)]
final class MedicinalProductDefinitionEverythingOperation
{
}

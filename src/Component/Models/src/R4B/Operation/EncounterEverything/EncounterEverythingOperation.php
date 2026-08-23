<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\EncounterEverything;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BundleResource;

#[FhirOperation(
    code: 'everything',
    url: 'http://hl7.org/fhir/OperationDefinition/Encounter-everything',
    version: 'R4B',
    inputClass: EncounterEverythingInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: BundleResource::class,
    resource: ['Encounter'],
    instance: true,
    type: false,
    system: false,
)]
final class EncounterEverythingOperation
{
}

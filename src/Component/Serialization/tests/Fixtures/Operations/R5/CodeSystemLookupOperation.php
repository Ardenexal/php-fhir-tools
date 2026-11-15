<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

/**
 * Hand-written stand-in for the generated R5 `CodeSystem/$lookup` holder.
 *
 * Carries invocation metadata only — it has no state and is never instantiated with data. The typed
 * payloads are {@see CodeSystemLookupInput} and {@see CodeSystemLookupOutput}.
 *
 * Note `instance: true`, where the R4 holder has `instance: false`. R4 declares `$lookup` as
 * type-level only; R5 adds instance-level invocation. Neither version allows system-level. This one
 * field is the whole difference between the two holders, and it is a concrete instance of the
 * milestone's thesis: the versions diverge in metadata, not in mapper logic.
 */
#[FhirOperation(
    code: 'lookup',
    url: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    version: 'R5',
    inputClass: CodeSystemLookupInput::class,
    outputShape: OperationOutputShape::Parameters,
    outputClass: CodeSystemLookupOutput::class,
    resource: ['CodeSystem'],
    instance: true,
    type: true,
    system: false,
)]
final class CodeSystemLookupOperation
{
}

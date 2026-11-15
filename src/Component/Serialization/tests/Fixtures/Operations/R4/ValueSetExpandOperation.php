<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSetResource;

/**
 * Hand-written stand-in for the generated R4 `ValueSet/$expand` holder — the class-B shape.
 *
 * `$expand` declares exactly one OUT parameter, named `return` and typed `ValueSet`. The R4
 * specification is explicit about what that means on the wire (`hl7.org/fhir/R4/operations.html`,
 * verbatim):
 *
 * > "If there is only one _out_ parameter, which is a Resource with the parameter name "return"
 * > then the parameter format is not used, and the response is simply the resource itself."
 *
 * R5 carries the same sentence. So the response is **literally un-wrapped** — not a `Parameters`
 * that everyone conventionally unwraps — and `outputClass` therefore points at the resource itself
 * rather than at a generated Output class. There is no `ValueSetExpandOutput`, by design.
 *
 * This is the majority shape: 57% of R4 operations and 64% of R5 (`inventory.md`). `$lookup` alone
 * would have proven the minority case and left this one to the generator.
 */
#[FhirOperation(
    code: 'expand',
    url: 'http://hl7.org/fhir/OperationDefinition/ValueSet-expand',
    version: 'R4',
    inputClass: ValueSetExpandInput::class,
    outputShape: OperationOutputShape::BareResource,
    outputClass: ValueSetResource::class,
    resource: ['ValueSet'],
    instance: true,
    type: true,
    system: false,
)]
final class ValueSetExpandOperation
{
}

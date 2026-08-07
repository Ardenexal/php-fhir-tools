<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSetResource;

/**
 * Hand-written stand-in for the generated R5 `ValueSet/$expand` holder — the class-B shape.
 *
 * See {@see \Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\ValueSetExpandOperation}
 * for the normative wording behind `BareResource`. R5 states it identically.
 *
 * Unlike `$lookup`, `$expand`'s invocation levels do **not** differ between R4 and R5 — both allow
 * instance and type. The versions diverge on the IN parameter list instead: R5 adds `useSupplement`
 * and `property`, taking the count from 21 to 23.
 */
#[FhirOperation(
    code: 'expand',
    url: 'http://hl7.org/fhir/OperationDefinition/ValueSet-expand',
    version: 'R5',
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

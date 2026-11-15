<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;

/**
 * Hand-written stand-in for the generated R4 `Measure/$submit-data` holder — the class-D shape.
 *
 * `$submit-data` declares two IN parameters and **no OUT parameters at all**: a successful
 * invocation answers with an HTTP status and no body. Three R4 operations are shaped this way
 * (2 in R5).
 *
 * Modelled explicitly rather than as "an Output that happened to come back empty", because those two
 * situations need to stay distinguishable — an empty Output object is also what a failed parse
 * produces, and silently treating one as the other turns a broken response into an apparently
 * successful call. `fromResponse()` returns `null` here, and treats a non-null body as a contract
 * violation rather than ignoring it.
 *
 * Note the hyphen in the operation code: `submit-data` is not a legal PHP identifier, which is why
 * the holder is named `MeasureSubmitDataOperation`. M02 owns proving that slugging generally
 * (D3's guard chain); this fixture is one hand-made instance of the result.
 */
#[FhirOperation(
    code: 'submit-data',
    url: 'http://hl7.org/fhir/OperationDefinition/Measure-submit-data',
    version: 'R4',
    inputClass: MeasureSubmitDataInput::class,
    outputShape: OperationOutputShape::NoOutput,
    outputClass: null,
    resource: ['Measure'],
    instance: true,
    type: true,
    system: false,
)]
final class MeasureSubmitDataOperation
{
}

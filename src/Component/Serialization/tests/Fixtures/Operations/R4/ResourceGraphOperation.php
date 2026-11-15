<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;

/**
 * Hand-written stand-in for the generated R4 `Resource/$graph` holder — the class-C shape.
 *
 * Class C is the shape the specification's un-wrap rule does **not** cover. That rule is conditioned
 * on the parameter's name (`hl7.org/fhir/R4/operations.html`):
 *
 * > "If there is only one *out* parameter, which is a Resource with the parameter name **"return"**
 * > then the parameter format is not used, and the response is simply the resource itself."
 *
 * `$graph`'s sole OUT parameter is a `Bundle` named **`result`**, not `return`. It therefore fails
 * the rule's condition, the parameter format *is* used, and the Bundle arrives wrapped in a
 * one-parameter `Parameters`. That is the entire difference between this shape and
 * {@see ValueSetExpandOperation}, and it is why `outputParameterName` exists — the name cannot be
 * assumed, and without it the wrapper cannot be read or written.
 *
 * Only 3 operations per version are class C, but collapsing them into class B would be wrong in both
 * directions: reading a wrapped body as though it were bare, and emitting a bare body that a server
 * would have to guess at.
 */
#[FhirOperation(
    code: 'graph',
    url: 'http://hl7.org/fhir/OperationDefinition/Resource-graph',
    version: 'R4',
    inputClass: ResourceGraphInput::class,
    outputShape: OperationOutputShape::NamedBareResource,
    outputClass: BundleResource::class,
    resource: ['Resource'],
    instance: true,
    type: false,
    system: false,
    outputParameterName: 'result',
)]
final class ResourceGraphOperation
{
}

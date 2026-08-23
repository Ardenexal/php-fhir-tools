<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\ParametersResource;

/**
 * Stands in for an Implementation Guide's profiled `Parameters`.
 *
 * A real IG-generated profile class is a subclass of the base resource with narrowed cardinalities
 * and a `meta.profile` default. Nothing about that shape matters here — what matters is that it is
 * a *different class* from `ParametersResource`, registered with the type resolver, so a mapper that
 * built its namespace by hand would silently produce the base class instead.
 */
final class ProfiledParametersResource extends ParametersResource
{
}

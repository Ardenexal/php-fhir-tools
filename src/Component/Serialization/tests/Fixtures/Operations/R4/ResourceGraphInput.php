<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;

/**
 * The IN side of R4 `Resource/$graph`: a single required `uri`.
 *
 * Exists so {@see ResourceGraphOperation} can point `inputClass` at something real. `$graph` is
 * carried here for its class-C **output** shape, so the input is deliberately minimal.
 */
final class ResourceGraphInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'graph',
            phpName: 'graph',
            use: 'in',
            min: 1,
            max: '1',
            type: 'uri',
        )]
        public readonly ?string $graph = null,
    ) {
    }
}

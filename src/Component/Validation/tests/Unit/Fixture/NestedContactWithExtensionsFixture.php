<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;

final class NestedContactWithExtensionsFixture
{
    use FHIRExtensionsTrait;

    /** @param list<FHIRExtensionInterface> $extension */
    public function __construct(
        private readonly array $extension = [],
    ) {
    }
}

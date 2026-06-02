<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

final class NoExtensionsTraitFixture
{
    public function __construct(
        public readonly string $name = 'test',
    ) {
    }
}

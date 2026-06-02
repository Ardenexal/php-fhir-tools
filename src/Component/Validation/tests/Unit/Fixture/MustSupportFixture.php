<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMustSupport;

final class MustSupportFixture
{
    public function __construct(
        #[FHIRMustSupport]
        public readonly ?string $name,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;

final class ObligationFixture
{
    public function __construct(
        #[FHIRObligation(code: 'SHALL:populate', actor: 'http://example.org/actor/placer')]
        public readonly ?string $shallPopulate = null,
        #[FHIRObligation(code: 'SHOULD:populate', actor: 'http://example.org/actor/placer')]
        public readonly ?string $shouldPopulate = null,
        #[FHIRObligation(code: 'SHALL:populate-if-known', actor: 'http://example.org/actor/placer')]
        public readonly ?string $shallPopulateIfKnown = null,
        #[FHIRObligation(code: 'SHALL:populate')]
        public readonly ?string $shallPopulateAllActors = null,
        #[FHIRObligation(code: 'SHALL:no-error', actor: 'http://example.org/actor/placer')]
        public readonly ?string $noErrorProperty = null,
        #[FHIRObligation(code: 'SHALL:populate', actor: 'http://example.org/actor/placer', filter: 'name.exists()')]
        public readonly ?string $filteredObligation = null,
    ) {
    }
}

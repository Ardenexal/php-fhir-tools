<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;

/**
 * Obligations whose FHIRPath filter selects occurrences of the element, the shape of the obligation
 * extension's own example: populate a home phone, whatever other contact points there are.
 */
final class FilteredObligationFixture
{
    /**
     * @param list<FilterContactPoint> $telecom
     * @param list<FilterContactPoint> $brokenFilter
     * @param list<FilterContactPoint> $nonBooleanFilter
     * @param list<FilterContactPoint> $noErrorHome
     */
    public function __construct(
        #[FHIRObligation(code: 'SHALL:populate', actor: 'http://example.org/actor/placer', filter: "use = 'home'")]
        public readonly array $telecom = [],
        #[FHIRObligation(code: 'SHOULD:populate', actor: 'http://example.org/actor/placer', filter: "use = 'home'")]
        public readonly ?FilterContactPoint $primary = null,
        #[FHIRObligation(code: 'SHALL:populate', actor: 'http://example.org/actor/placer', filter: 'use = (')]
        public readonly array $brokenFilter = [],
        #[FHIRObligation(code: 'SHALL:populate', actor: 'http://example.org/actor/placer', filter: 'use')]
        public readonly array $nonBooleanFilter = [],
        #[FHIRObligation(code: 'SHALL:no-error', actor: 'http://example.org/actor/placer', filter: "use = 'home'")]
        public readonly array $noErrorHome = [],
    ) {
    }
}

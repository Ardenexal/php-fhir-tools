<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRHttpClientInterface;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Sdc\XFhirQueryPopulationDataProvider;
use PHPUnit\Framework\TestCase;

final class XFhirQueryPopulationDataProviderTest extends TestCase
{
    public function testExtractsEntryResourcesFromResultBundle(): void
    {
        $bundle   = $this->searchsetWithTwoObservations();
        $provider = new XFhirQueryPopulationDataProvider(new StubFHIRHttpClient($bundle));

        $resources = $provider->resourcesForQuery('Observation?subject=Patient/1', 'R4');

        self::assertIsArray($resources);
        self::assertCount(2, $resources);
        self::assertSame([true, true], array_map('is_object', $resources));
    }

    public function testReturnsNullOnFetchFailure(): void
    {
        $provider = new XFhirQueryPopulationDataProvider(new StubFHIRHttpClient(null));

        self::assertNull(
            $provider->resourcesForQuery('Observation?subject=Patient/1', 'R4'),
            'A null from the client (fetch failure) must surface as null, distinct from an empty match set.',
        );
    }

    public function testEmptySearchsetYieldsEmptyList(): void
    {
        $empty = FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromJson(
            '{"resourceType":"Bundle","type":"searchset","total":0}',
            BundleResource::class,
        );
        $provider = new XFhirQueryPopulationDataProvider(new StubFHIRHttpClient($empty));

        self::assertSame([], $provider->resourcesForQuery('Observation?subject=Patient/1', 'R4'));
    }

    /**
     * Searchset entry filter (M04): a searchset may carry `_include`d resources (`search.mode = 'include'`)
     * and `OperationOutcome` entries (`search.mode = 'outcome'`) alongside the actual matches. Only
     * `search.mode = 'match'` entries are population data; the others must not be bound as spurious
     * `%<name>` context results.
     */
    public function testFiltersOutNonMatchSearchModeEntries(): void
    {
        $bundle = FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromJson(
            json_encode([
                'resourceType' => 'Bundle',
                'type'         => 'searchset',
                'entry'        => [
                    [
                        'resource' => ['resourceType' => 'Observation', 'id' => 'match-1', 'status' => 'final', 'code' => []],
                        'search'   => ['mode' => 'match'],
                    ],
                    [
                        'resource' => ['resourceType' => 'Patient', 'id' => 'included-1'],
                        'search'   => ['mode' => 'include'],
                    ],
                    [
                        'resource' => ['resourceType' => 'OperationOutcome', 'id' => 'outcome-1', 'issue' => []],
                        'search'   => ['mode' => 'outcome'],
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
            BundleResource::class,
        );
        $provider = new XFhirQueryPopulationDataProvider(new StubFHIRHttpClient($bundle));

        $resources = $provider->resourcesForQuery('Observation?subject=Patient/1', 'R4');

        self::assertIsArray($resources);
        self::assertCount(1, $resources);
        self::assertSame('match-1', $resources[0]->id ?? null);
    }

    private function searchsetWithTwoObservations(): object
    {
        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromJson(
            json_encode([
                'resourceType' => 'Bundle',
                'type'         => 'searchset',
                'entry'        => [
                    [
                        'resource' => ['resourceType' => 'Observation', 'id' => 'o1', 'status' => 'final', 'code' => []],
                        'search'   => ['mode' => 'match'],
                    ],
                    [
                        'resource' => ['resourceType' => 'Observation', 'id' => 'o2', 'status' => 'final', 'code' => []],
                        'search'   => ['mode' => 'match'],
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
            BundleResource::class,
        );
    }

    // -------------------------------------------------------------------------
    // Multi-page result following (M06)
    // -------------------------------------------------------------------------

    public function testFollowsNextLinkAcrossMultiplePages(): void
    {
        $page1 = $this->pageWithOneMatch('p1', 'https://fhir.example.com/Observation?page=2');
        $page2 = $this->pageWithOneMatch('p2', null); // no further next link — pagination ends naturally

        $provider  = new XFhirQueryPopulationDataProvider(new StubFHIRHttpClient($page1, [$page2]));
        $resources = $provider->resourcesForQuery('Observation?subject=Patient/1', 'R4');

        self::assertIsArray($resources);
        self::assertSame(['p1', 'p2'], array_map(static fn ($r) => $r->id ?? null, $resources));
    }

    public function testStopsPaginationWhenNextPageFetchFails(): void
    {
        $page1 = $this->pageWithOneMatch('p1', 'https://fhir.example.com/Observation?page=2');

        // followLink() returns null (transport failure, or an off-host link FHIRHttpClient rejected) —
        // page 1 already succeeded, so the result is the accumulated (partial) list, not null.
        $provider  = new XFhirQueryPopulationDataProvider(new StubFHIRHttpClient($page1, [null]));
        $resources = $provider->resourcesForQuery('Observation?subject=Patient/1', 'R4');

        self::assertSame(['p1'], array_map(static fn ($r) => $r->id ?? null, $resources ?? []));
    }

    public function testPageLimitBoundsIterationCount(): void
    {
        // Every page (including the last one the provider is allowed to fetch) carries a `next` link, so
        // without a bound this would loop forever. 60 pages are available; MAX_PAGES = 50 must stop the
        // provider at exactly 50 total pages (page 1 + 49 followLink() calls), never touching pages 51-60.
        $page1 = $this->pageWithOneMatch('page-1', 'https://fhir.example.com/Observation?page=2');

        $subsequentPages = [];
        for ($i = 2; $i <= 60; ++$i) {
            $subsequentPages[] = $this->pageWithOneMatch("page-{$i}", 'https://fhir.example.com/Observation?page=' . ($i + 1));
        }

        $provider  = new XFhirQueryPopulationDataProvider(new StubFHIRHttpClient($page1, $subsequentPages));
        $resources = $provider->resourcesForQuery('Observation?subject=Patient/1', 'R4');

        self::assertIsArray($resources);
        self::assertCount(50, $resources, 'Must stop at exactly 50 total pages, not follow all 60 available.');
        self::assertSame('page-50', end($resources)->id ?? null);
    }

    private function pageWithOneMatch(string $id, ?string $nextUrl): object
    {
        $link = $nextUrl !== null ? [['relation' => 'next', 'url' => $nextUrl]] : [];

        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromJson(
            json_encode([
                'resourceType' => 'Bundle',
                'type'         => 'searchset',
                'link'         => $link,
                'entry'        => [
                    [
                        'resource' => ['resourceType' => 'Observation', 'id' => $id, 'status' => 'final', 'code' => []],
                        'search'   => ['mode' => 'match'],
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
            BundleResource::class,
        );
    }
}

/**
 * Stub FHIR HTTP client returning a fixed search Bundle (or null to simulate a fetch failure), then
 * successive pages (or null) from $subsequentPages on each followLink() call, in order — the last item
 * having no further `next` link ends pagination naturally.
 */
final class StubFHIRHttpClient implements FHIRHttpClientInterface
{
    private int $followLinkCalls = 0;

    /**
     * @param list<object|null> $subsequentPages
     */
    public function __construct(
        private readonly ?object $bundle,
        private readonly array $subsequentPages = [],
    ) {
    }

    public function search(string $search, string $fhirVersion): ?object
    {
        return $this->bundle;
    }

    public function followLink(string $url, string $fhirVersion): ?object
    {
        $page = $this->subsequentPages[$this->followLinkCalls] ?? null;
        ++$this->followLinkCalls;

        return $page;
    }

    public function request(string $method, string $path, ?string $body = null, array $headers = []): ?string
    {
        return null;
    }
}

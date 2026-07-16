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

    private function searchsetWithTwoObservations(): object
    {
        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromJson(
            json_encode([
                'resourceType' => 'Bundle',
                'type'         => 'searchset',
                'entry'        => [
                    ['resource' => ['resourceType' => 'Observation', 'id' => 'o1', 'status' => 'final', 'code' => []]],
                    ['resource' => ['resourceType' => 'Observation', 'id' => 'o2', 'status' => 'final', 'code' => []]],
                ],
            ], JSON_THROW_ON_ERROR),
            BundleResource::class,
        );
    }
}

/**
 * Stub FHIR HTTP client returning a fixed search Bundle (or null to simulate a fetch failure).
 */
final class StubFHIRHttpClient implements FHIRHttpClientInterface
{
    public function __construct(private readonly ?object $bundle)
    {
    }

    public function search(string $search, string $fhirVersion): ?object
    {
        return $this->bundle;
    }

    public function request(string $method, string $path, ?string $body = null, array $headers = []): ?string
    {
        return null;
    }
}

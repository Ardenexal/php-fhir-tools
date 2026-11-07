<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Validation\Profile\BusinessVersion;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\FhirReleaseLabel;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InMemoryStructureDefinitionRegistry::class)]
#[CoversClass(PackageDirectoryStructureDefinitionLoader::class)]
#[CoversClass(CompositeStructureDefinitionRegistry::class)]
#[CoversClass(FhirReleaseLabel::class)]
#[CoversClass(BusinessVersion::class)]
final class StructureDefinitionRegistryTest extends TestCase
{
    private const string PACKAGE = __DIR__ . '/../../Fixtures/Profile/package-r4';

    private const string BP = 'http://hl7.org/fhir/StructureDefinition/bp';

    private const string VERSIONED = 'http://example.org/fhir/StructureDefinition/versioned';

    /** @var list<string> */
    private array $tempDirs = [];

    protected function tearDown(): void
    {
        foreach ($this->tempDirs as $dir) {
            self::removeDirectory($dir);
        }
    }

    public function testInMemoryLookupIsKeyedByFhirRelease(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry([
            ['url' => self::BP, 'fhirVersion' => '4.0.1', 'id' => 'r4'],
            ['url' => self::BP, 'fhirVersion' => '4.3.0', 'id' => 'r4b'],
        ]);

        self::assertSame('r4', $registry->find('R4', self::BP)['id'] ?? null);
        self::assertSame('r4b', $registry->find('R4B', self::BP)['id'] ?? null);
        self::assertNull($registry->find('R5', self::BP));
        self::assertSame(['R4', 'R4B'], $registry->fhirVersionsFor(self::BP));
    }

    public function testInMemoryLookupWithoutVersionReturnsHighestBusinessVersion(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry();
        foreach (['1.0.0', '1.10.0', '1.2.0'] as $version) {
            $registry->add(['url' => self::VERSIONED, 'version' => $version], 'R4');
        }

        self::assertSame('1.10.0', $registry->find('R4', self::VERSIONED)['version'] ?? null);
        self::assertSame('1.2.0', $registry->find('R4', self::VERSIONED, '1.2.0')['version'] ?? null);
        self::assertNull($registry->find('R4', self::VERSIONED, '9.9.9'));
    }

    public function testNumericBusinessVersionsSurviveArrayKeyConversion(): void
    {
        // PHP stores the key '2' as int 2; an unpinned lookup must still compare it as a version.
        $registry = new InMemoryStructureDefinitionRegistry();
        foreach (['1', '2'] as $version) {
            $registry->add(['url' => self::VERSIONED, 'version' => $version], 'R4');
        }

        self::assertSame('2', $registry->find('R4', self::VERSIONED)['version'] ?? null);
        self::assertSame('1', $registry->find('R4', self::VERSIONED, '1')['version'] ?? null);
    }

    public function testInMemoryRejectsDefinitionWithoutFhirRelease(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot tell which FHIR release');

        new InMemoryStructureDefinitionRegistry([['url' => self::BP]]);
    }

    public function testPackageLoaderReadsIndexFileAndPackageRelease(): void
    {
        $loader = new PackageDirectoryStructureDefinitionLoader(self::PACKAGE);

        self::assertSame('bp', $loader->find('R4', self::BP)['id'] ?? null);
        self::assertSame('1.10.0', $loader->find('R4', self::VERSIONED)['version'] ?? null);
        self::assertSame('1.0.0', $loader->find('R4', self::VERSIONED, '1.0.0')['version'] ?? null);
        self::assertNull($loader->find('R4B', self::BP));
        self::assertNull($loader->find('R4', 'http://example.org/fhir/ValueSet/example'));
    }

    public function testPackageLoaderScansDirectoryWithoutIndexFile(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        file_put_contents($dir . '/notes.json', '{ not json');

        $loader = new PackageDirectoryStructureDefinitionLoader($dir);

        self::assertSame('bp', $loader->find('R4', self::BP)['id'] ?? null);
        self::assertSame('1.10.0', $loader->find('R4', self::VERSIONED)['version'] ?? null);
        self::assertSame(['R4'], $loader->fhirVersionsFor(self::BP));
    }

    public function testScanFindsStructureDefinitionWhoseContainedResourceComesFirst(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        file_put_contents($dir . '/StructureDefinition-contained-first.json', json_encode([
            'contained'    => [['resourceType' => 'ValueSet', 'id' => 'local']],
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://example.org/fhir/StructureDefinition/contained-first',
            'fhirVersion'  => '4.0.1',
        ], JSON_THROW_ON_ERROR));

        $loader = new PackageDirectoryStructureDefinitionLoader($dir);

        self::assertNotNull($loader->find('R4', 'http://example.org/fhir/StructureDefinition/contained-first'));
    }

    public function testPackageLoaderTakesReleaseFromEachDefinitionWhenManifestIsMissing(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        unlink($dir . '/package.json');

        $loader = new PackageDirectoryStructureDefinitionLoader($dir);

        self::assertSame('bp', $loader->find('R4', self::BP)['id'] ?? null);
    }

    public function testPackageLoaderAcceptsExtractedTarballRoot(): void
    {
        $root = $this->tempDir();
        $this->copyInto(self::PACKAGE, $root . '/package', withIndex: true);

        $loader = new PackageDirectoryStructureDefinitionLoader($root);

        self::assertSame('bp', $loader->find('R4', self::BP)['id'] ?? null);
    }

    public function testPackageLoaderFailsOnBrokenStructureDefinitionFile(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        file_put_contents($dir . '/StructureDefinition-broken.json', '{"resourceType": "StructureDefinition", "url": ');

        $loader = new PackageDirectoryStructureDefinitionLoader($dir);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('StructureDefinition-broken.json');
        $loader->find('R4', self::BP);
    }

    public function testPackageLoaderRejectsMissingDirectory(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PackageDirectoryStructureDefinitionLoader(self::PACKAGE . '/does-not-exist');
    }

    public function testCompositeReturnsFirstMatchInOrder(): void
    {
        $override  = new InMemoryStructureDefinitionRegistry([['url' => self::BP, 'fhirVersion' => 'R4', 'id' => 'local']]);
        $composite = new CompositeStructureDefinitionRegistry([$override, new PackageDirectoryStructureDefinitionLoader(self::PACKAGE)]);

        self::assertSame('local', $composite->find('R4', self::BP)['id'] ?? null);
        self::assertSame('vitalsigns', $composite->find('R4', 'http://hl7.org/fhir/StructureDefinition/vitalsigns')['id'] ?? null);
        self::assertSame(['R4'], $composite->fhirVersionsFor(self::BP));
    }

    private function copyPackage(bool $withIndex): string
    {
        $dir = $this->tempDir();
        $this->copyInto(self::PACKAGE, $dir, $withIndex);

        return $dir;
    }

    private function copyInto(string $from, string $to, bool $withIndex): void
    {
        if (!is_dir($to)) {
            mkdir($to);
        }
        foreach (glob($from . '/*.json') ?: [] as $file) {
            copy($file, $to . '/' . basename($file));
        }
        if ($withIndex) {
            copy($from . '/.index.json', $to . '/.index.json');
        }
    }

    private function tempDir(): string
    {
        $dir = sys_get_temp_dir() . '/fhir-profile-registry-' . bin2hex(random_bytes(6));
        mkdir($dir);
        $this->tempDirs[] = $dir;

        return $dir;
    }

    private static function removeDirectory(string $dir): void
    {
        foreach (array_diff(scandir($dir) ?: [], ['.', '..']) as $entry) {
            $path = $dir . '/' . $entry;
            is_dir($path) ? self::removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Validation\Profile\BusinessVersion;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\FhirReleaseLabel;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\UnreadableStructureDefinitionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InMemoryStructureDefinitionRegistry::class)]
#[CoversClass(PackageDirectoryStructureDefinitionRegistry::class)]
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

        self::assertSame('r4', $registry->find(self::BP, 'R4')['id'] ?? null);
        self::assertSame('r4b', $registry->find(self::BP, 'R4B')['id'] ?? null);
        self::assertNull($registry->find(self::BP, 'R5'));
        self::assertSame(['R4', 'R4B'], $registry->fhirVersionsFor(self::BP));
    }

    public function testInMemoryLookupWithoutVersionReturnsHighestBusinessVersion(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry();
        foreach (['1.0.0', '1.10.0', '1.2.0'] as $version) {
            $registry->add(['url' => self::VERSIONED, 'version' => $version], 'R4');
        }

        self::assertSame('1.10.0', $registry->find(self::VERSIONED, 'R4')['version'] ?? null);
        self::assertSame('1.2.0', $registry->find(self::VERSIONED, 'R4', '1.2.0')['version'] ?? null);
        self::assertNull($registry->find(self::VERSIONED, 'R4', '9.9.9'));
    }

    public function testNumericBusinessVersionsSurviveArrayKeyConversion(): void
    {
        // PHP stores the key '2' as int 2; an unpinned lookup must still compare it as a version.
        $registry = new InMemoryStructureDefinitionRegistry();
        foreach (['1', '2'] as $version) {
            $registry->add(['url' => self::VERSIONED, 'version' => $version], 'R4');
        }

        self::assertSame('2', $registry->find(self::VERSIONED, 'R4')['version'] ?? null);
        self::assertSame('1', $registry->find(self::VERSIONED, 'R4', '1')['version'] ?? null);
    }

    public function testInMemoryRejectsDefinitionWithoutFhirRelease(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot tell which FHIR release');

        new InMemoryStructureDefinitionRegistry([['url' => self::BP]]);
    }

    public function testInMemoryRegistryReadsStructureDefinitionFiles(): void
    {
        $dir = $this->tempDir();
        file_put_contents($dir . '/bp.json', (string) json_encode(['resourceType' => 'StructureDefinition', 'url' => self::BP, 'fhirVersion' => '4.0.1', 'id' => 'from-file']));

        $registry = InMemoryStructureDefinitionRegistry::fromFiles([$dir . '/bp.json']);

        self::assertSame('from-file', $registry->find(self::BP, 'R4')['id'] ?? null);
    }

    public function testInMemoryRegistryRejectsAFileThatIsNotAStructureDefinition(): void
    {
        $dir = $this->tempDir();
        file_put_contents($dir . '/patient.json', '{"resourceType":"Patient"}');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not hold a StructureDefinition');

        InMemoryStructureDefinitionRegistry::fromFiles([$dir . '/patient.json']);
    }

    public function testInMemoryRegistryRejectsAFileThatIsNotJson(): void
    {
        $dir = $this->tempDir();
        file_put_contents($dir . '/broken.json', '{"resourceType":');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('is not valid JSON');

        InMemoryStructureDefinitionRegistry::fromFiles([$dir . '/broken.json']);
    }

    public function testInMemoryRegistryRejectsAMissingFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cannot be read');

        InMemoryStructureDefinitionRegistry::fromFiles(['/nonexistent/sd.json']);
    }

    public function testPackageLoaderReadsIndexFileAndPackageRelease(): void
    {
        $loader = new PackageDirectoryStructureDefinitionRegistry(self::PACKAGE);

        self::assertSame('bp', $loader->find(self::BP, 'R4')['id'] ?? null);
        self::assertSame('1.10.0', $loader->find(self::VERSIONED, 'R4')['version'] ?? null);
        self::assertSame('1.0.0', $loader->find(self::VERSIONED, 'R4', '1.0.0')['version'] ?? null);
        self::assertNull($loader->find(self::BP, 'R4B'));
        self::assertNull($loader->find('http://example.org/fhir/ValueSet/example', 'R4'));
    }

    public function testPackageLoaderScansDirectoryWithoutIndexFile(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        file_put_contents($dir . '/notes.json', '{ not json');

        $loader = new PackageDirectoryStructureDefinitionRegistry($dir);

        self::assertSame('bp', $loader->find(self::BP, 'R4')['id'] ?? null);
        self::assertSame('1.10.0', $loader->find(self::VERSIONED, 'R4')['version'] ?? null);
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

        $loader = new PackageDirectoryStructureDefinitionRegistry($dir);

        self::assertNotNull($loader->find('http://example.org/fhir/StructureDefinition/contained-first', 'R4'));
    }

    public function testPackageLoaderTakesReleaseFromEachDefinitionWhenManifestIsMissing(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        unlink($dir . '/package.json');

        $loader = new PackageDirectoryStructureDefinitionRegistry($dir);

        self::assertSame('bp', $loader->find(self::BP, 'R4')['id'] ?? null);
    }

    public function testPackageLoaderAcceptsExtractedTarballRoot(): void
    {
        $root = $this->tempDir();
        $this->copyInto(self::PACKAGE, $root . '/package', withIndex: true);

        $loader = new PackageDirectoryStructureDefinitionRegistry($root);

        self::assertSame('bp', $loader->find(self::BP, 'R4')['id'] ?? null);
    }

    /**
     * The broken file's URL is unknown, so the scan cannot leave it out of one lookup only. It fails
     * every lookup, and keeps the failure rather than reading the whole directory again each time.
     */
    public function testPackageLoaderFailsEveryLookupOnceAScannedStructureDefinitionIsBroken(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        file_put_contents($dir . '/StructureDefinition-broken.json', '{"resourceType": "StructureDefinition", "url": ');

        $loader = new PackageDirectoryStructureDefinitionRegistry($dir);

        $first = self::unreadable(static fn () => $loader->find(self::BP, 'R4'));
        self::assertStringContainsString('StructureDefinition-broken.json', $first->getMessage());

        unlink($dir . '/StructureDefinition-broken.json');
        self::assertSame($first, self::unreadable(static fn () => $loader->fhirVersionsFor(self::BP)));
    }

    /**
     * `.index.json` names each file's URL, so a file it lists that is missing fails only that URL.
     */
    public function testPackageLoaderFailsOnlyTheLookupOfAnIndexedFileThatIsMissing(): void
    {
        $dir = $this->copyPackage(withIndex: true);
        unlink($dir . '/StructureDefinition-bp.json');

        $loader = new PackageDirectoryStructureDefinitionRegistry($dir);

        self::assertStringContainsString('StructureDefinition-bp.json', self::unreadable(static fn () => $loader->find(self::BP, 'R4'))->getMessage());
        self::assertSame('vitalsigns', $loader->find('http://hl7.org/fhir/StructureDefinition/vitalsigns', 'R4')['id'] ?? null);
    }

    public function testPackageLoaderRejectsMissingDirectory(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PackageDirectoryStructureDefinitionRegistry(self::PACKAGE . '/does-not-exist');
    }

    public function testCompositeReturnsFirstMatchInOrder(): void
    {
        $override  = new InMemoryStructureDefinitionRegistry([['url' => self::BP, 'fhirVersion' => 'R4', 'id' => 'local']]);
        $composite = new CompositeStructureDefinitionRegistry([$override, new PackageDirectoryStructureDefinitionRegistry(self::PACKAGE)]);

        self::assertSame('local', $composite->find(self::BP, 'R4')['id'] ?? null);
        self::assertSame('vitalsigns', $composite->find('http://hl7.org/fhir/StructureDefinition/vitalsigns', 'R4')['id'] ?? null);
        self::assertSame(['R4'], $composite->fhirVersionsFor(self::BP));
    }

    public function testCompositeLooksPastARegistryThatCannotReadAndRethrowsWhenNothingAnswers(): void
    {
        $dir = $this->copyPackage(withIndex: false);
        file_put_contents($dir . '/StructureDefinition-broken.json', '{"resourceType": "StructureDefinition", "url": ');
        $broken = new PackageDirectoryStructureDefinitionRegistry($dir);
        $local  = new InMemoryStructureDefinitionRegistry([['url' => self::BP, 'fhirVersion' => 'R4', 'id' => 'local']]);

        $composite = new CompositeStructureDefinitionRegistry([$broken, $local]);

        self::assertSame('local', $composite->find(self::BP, 'R4')['id'] ?? null);
        self::assertSame(['R4'], $composite->fhirVersionsFor(self::BP));
        self::unreadable(static fn () => $composite->find(self::VERSIONED, 'R4'));
        self::unreadable(static fn () => $composite->fhirVersionsFor(self::VERSIONED));
    }

    /**
     * Run a lookup that must fail to read a definition, and return why.
     *
     * @param \Closure(): mixed $lookup
     */
    private static function unreadable(\Closure $lookup): UnreadableStructureDefinitionException
    {
        try {
            $lookup();
        } catch (UnreadableStructureDefinitionException $e) {
            return $e;
        }

        self::fail('The lookup did not report an unreadable definition.');
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

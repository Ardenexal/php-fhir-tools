<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit;

use Ardenexal\FHIRTools\PackageLoader;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Ardenexal\FHIRTools\BuilderContext;

/**
 * Unit tests for PackageLoader functionality
 *
 * This test class verifies the FHIR package loading functionality
 * including basic instantiation and dependency injection.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class PackageLoaderTest extends TestCase
{
    private PackageLoader $packageLoader;

    private string $tempCacheDir;

    protected function setUp(): void
    {
        $this->tempCacheDir = $this->createTempDirectory();

        // Create mock dependencies
        $httpClient     = $this->createMock(HttpClientInterface::class);
        $builderContext = $this->createMock(BuilderContext::class);
        $filesystem     = new Filesystem();

        $this->packageLoader = new PackageLoader(
            $httpClient,
            $this->tempCacheDir,
            $builderContext,
            $filesystem,
        );
    }

    protected function tearDown(): void
    {
        $this->cleanupTempDirectory($this->tempCacheDir);
    }

    /**
     * Test PackageLoader instantiation
     */
    public function testPackageLoaderInstantiation(): void
    {
        self::assertInstanceOf(PackageLoader::class, $this->packageLoader);
    }

    /**
     * Test cache directory creation
     */
    public function testCacheDirectoryHandling(): void
    {
        self::assertDirectoryExists($this->tempCacheDir);
        self::assertTrue(is_writable($this->tempCacheDir));
    }
}

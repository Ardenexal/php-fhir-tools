<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\CacheIntegrityManager;
use Ardenexal\FHIRTools\Component\Package\PackageMetadata;
use Ardenexal\FHIRTools\Exception\PackageException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Unit tests for CacheIntegrityManager
 *
 * @author FHIR Tools
 */
class CacheIntegrityManagerTest extends TestCase
{
    private CacheIntegrityManager $manager;

    private Filesystem $filesystem;

    private string $tempDir;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->manager    = new CacheIntegrityManager($this->filesystem, new NullLogger());
        $this->tempDir    = sys_get_temp_dir() . '/fhir-tools-test-' . uniqid();
        $this->filesystem->mkdir($this->tempDir);
    }

    protected function tearDown(): void
    {
        if ($this->filesystem->exists($this->tempDir)) {
            $this->filesystem->remove($this->tempDir);
        }
    }

    public function testGenerateChecksumForExistingFile(): void
    {
        $filePath = $this->tempDir . '/test-file.txt';
        $content  = 'test content';
        $this->filesystem->dumpFile($filePath, $content);

        $checksum = $this->manager->generateChecksum($filePath);
        self::assertNotEmpty($checksum);
        // Verify it's a valid SHA-256 hash (64 characters)
        self::assertSame(64, strlen($checksum));
    }

    public function testGenerateChecksumThrowsExceptionForNonExistentFile(): void
    {
        $filePath = $this->tempDir . '/nonexistent-file.txt';

        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('File not found');

        $this->manager->generateChecksum($filePath);
    }

    public function testGenerateChecksumThrowsExceptionForUnsupportedAlgorithm(): void
    {
        $filePath = $this->tempDir . '/test-file.txt';
        $this->filesystem->dumpFile($filePath, 'test content');

        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('Unsupported hash algorithm');

        $this->manager->generateChecksum($filePath, 'unsupported-algorithm');
    }

    public function testVerifyChecksumWithValidChecksum(): void
    {
        $filePath = $this->tempDir . '/test-file.txt';
        $content  = 'test content';
        $this->filesystem->dumpFile($filePath, $content);

        $expectedChecksum = hash('sha256', $content);

        $result = $this->manager->verifyChecksum($filePath, $expectedChecksum);

        self::assertTrue($result);
    }

    public function testVerifyChecksumWithInvalidChecksum(): void
    {
        $filePath = $this->tempDir . '/test-file.txt';
        $content  = 'test content';
        $this->filesystem->dumpFile($filePath, $content);

        $invalidChecksum = 'invalid-checksum';

        $result = $this->manager->verifyChecksum($filePath, $invalidChecksum);

        self::assertFalse($result);
    }

    public function testStoreIntegrityMetadata(): void
    {
        $cacheDir = $this->tempDir . '/cache';
        $this->filesystem->mkdir($cacheDir);

        $packageMetadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Test Package',
        );

        $checksum = 'abc123';

        $this->manager->storeIntegrityMetadata($cacheDir, $packageMetadata, $checksum);

        $integrityFile = $cacheDir . '/.integrity.json';
        self::assertTrue($this->filesystem->exists($integrityFile));

        $content = file_get_contents($integrityFile);
        if ($content === false) {
            throw new \RuntimeException('Failed to read integrity file');
        }
        $metadata = json_decode($content, true);
        self::assertSame('test-package', $metadata['package_name']);
        self::assertSame('1.0.0', $metadata['package_version']);
        self::assertSame('abc123', $metadata['checksum']);
    }

    public function testLoadIntegrityMetadata(): void
    {
        $cacheDir = $this->tempDir . '/cache';
        $this->filesystem->mkdir($cacheDir);

        $metadata = [
            'package_name'    => 'test-package',
            'package_version' => '1.0.0',
            'checksum'        => 'abc123',
            'algorithm'       => 'sha256',
            'created_at'      => date('c'),
            'file_count'      => 5,
            'total_size'      => 1024,
        ];

        $integrityFile = $cacheDir . '/.integrity.json';
        $metadataJson  = json_encode($metadata);
        if ($metadataJson === false) {
            throw new \RuntimeException('Failed to encode metadata');
        }
        $this->filesystem->dumpFile($integrityFile, $metadataJson);

        $result = $this->manager->loadIntegrityMetadata($cacheDir);

        self::assertSame($metadata, $result);
    }

    public function testLoadIntegrityMetadataReturnsNullWhenFileDoesNotExist(): void
    {
        $cacheDir = $this->tempDir . '/cache';
        $this->filesystem->mkdir($cacheDir);

        $result = $this->manager->loadIntegrityMetadata($cacheDir);

        self::assertNull($result);
    }

    public function testValidateCacheIntegrityWithValidCache(): void
    {
        $cacheDir = $this->tempDir . '/cache';
        $this->filesystem->mkdir($cacheDir);

        // Create some test files
        $this->filesystem->dumpFile($cacheDir . '/file1.json', '{"test": "data1"}');
        $this->filesystem->dumpFile($cacheDir . '/file2.json', '{"test": "data2"}');

        $packageFile    = $this->tempDir . '/package.tgz';
        $packageContent = 'test package content';
        $this->filesystem->dumpFile($packageFile, $packageContent);

        $packageMetadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Test Package',
        );

        $checksum = hash('sha256', $packageContent);

        // Store integrity metadata
        $this->manager->storeIntegrityMetadata($cacheDir, $packageMetadata, $checksum);

        $result = $this->manager->validateCacheIntegrity($cacheDir, $packageMetadata, $packageFile);

        self::assertTrue($result);
    }

    public function testCleanupCorruptedCache(): void
    {
        $cacheDir = $this->tempDir . '/corrupted-cache';
        $this->filesystem->mkdir($cacheDir);
        $this->filesystem->dumpFile($cacheDir . '/test-file.txt', 'test content');

        self::assertTrue($this->filesystem->exists($cacheDir));

        $this->manager->cleanupCorruptedCache($cacheDir);

        self::assertFalse($this->filesystem->exists($cacheDir));
    }
}

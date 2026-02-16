<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Manages package cache integrity verification with checksums
 *
 * This class provides comprehensive cache integrity management:
 *
 * - Checksum generation and verification for cached packages
 * - Cache corruption detection and automatic cleanup
 * - Multiple hash algorithms support (SHA-256, MD5, etc.)
 * - Integrity metadata storage and retrieval
 * - Cache validation and repair operations
 *
 * The integrity manager ensures that cached FHIR packages have not been
 * corrupted or tampered with, providing confidence in the cached data
 * and automatic recovery when corruption is detected.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Package
 */
class CacheIntegrityManager
{
    /**
     * Default hash algorithm for checksum generation
     */
    private const DEFAULT_HASH_ALGORITHM = 'sha256';

    /**
     * Integrity metadata file name
     */
    private const INTEGRITY_FILE = '.integrity.json';

    /**
     * Filesystem operations handler
     *
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * Logger for recording integrity operations
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Construct a new CacheIntegrityManager
     *
     * @param Filesystem           $filesystem Filesystem operations handler
     * @param LoggerInterface|null $logger     Logger for recording operations (optional)
     */
    public function __construct(Filesystem $filesystem, ?LoggerInterface $logger = null)
    {
        $this->filesystem = $filesystem;
        $this->logger     = $logger ?? new NullLogger();
    }

    /**
     * Generate checksum for a package file
     *
     * @param string $filePath  Path to the package file
     * @param string $algorithm Hash algorithm to use
     *
     * @return string Generated checksum
     *
     * @throws PackageException When file cannot be read or algorithm is unsupported
     */
    public function generateChecksum(string $filePath, string $algorithm = self::DEFAULT_HASH_ALGORITHM): string
    {
        if (!$this->filesystem->exists($filePath)) {
            throw PackageException::fileNotFound($filePath);
        }

        if (!in_array($algorithm, hash_algos(), true)) {
            throw PackageException::unsupportedHashAlgorithm($algorithm);
        }

        try {
            $checksum = hash_file($algorithm, $filePath);
            if ($checksum === false) {
                throw PackageException::checksumGenerationFailed($filePath, 'hash_file returned false');
            }

            $this->logger->debug("Generated {$algorithm} checksum for {$filePath}: {$checksum}");

            return $checksum;
        } catch (\Exception $e) {
            throw PackageException::checksumGenerationFailed($filePath, $e->getMessage());
        }
    }

    /**
     * Verify checksum for a package file
     *
     * @param string $filePath         Path to the package file
     * @param string $expectedChecksum Expected checksum
     * @param string $algorithm        Hash algorithm used
     *
     * @return bool True if checksum matches
     *
     * @throws PackageException When verification fails
     */
    public function verifyChecksum(
        string $filePath,
        string $expectedChecksum,
        string $algorithm = self::DEFAULT_HASH_ALGORITHM
    ): bool {
        $actualChecksum = $this->generateChecksum($filePath, $algorithm);

        $isValid = hash_equals($expectedChecksum, $actualChecksum);

        if ($isValid) {
            $this->logger->debug("Checksum verification passed for {$filePath}");
        } else {
            $this->logger->warning("Checksum verification failed for {$filePath}. Expected: {$expectedChecksum}, Actual: {$actualChecksum}");
        }

        return $isValid;
    }

    /**
     * Store integrity metadata for a cached package
     *
     * @param string          $cacheDir        Cache directory path
     * @param PackageMetadata $packageMetadata Package metadata
     * @param string          $checksum        Package checksum
     * @param string          $algorithm       Hash algorithm used
     *
     * @throws PackageException When metadata cannot be stored
     */
    public function storeIntegrityMetadata(
        string $cacheDir,
        PackageMetadata $packageMetadata,
        string $checksum,
        string $algorithm = self::DEFAULT_HASH_ALGORITHM
    ): void {
        $integrityFile = $cacheDir . '/' . self::INTEGRITY_FILE;
        $metadata      = [
            'package_name'    => $packageMetadata->getName(),
            'package_version' => $packageMetadata->getVersion(),
            'checksum'        => $checksum,
            'algorithm'       => $algorithm,
            'created_at'      => date('c'),
            'file_count'      => $this->countFiles($cacheDir),
            'total_size'      => $this->calculateDirectorySize($cacheDir),
            // Store full package metadata for offline reconstruction
            'package_metadata' => [
                'url'          => $packageMetadata->getUrl(),
                'description'  => $packageMetadata->getDescription(),
                'fhirVersions' => $packageMetadata->getFhirVersions(),
                'dependencies' => $packageMetadata->getDependencies(),
            ],
        ];

        try {
            $jsonContent = json_encode($metadata, JSON_PRETTY_PRINT);
            if ($jsonContent === false) {
                throw PackageException::integrityMetadataStoreFailed($packageMetadata->getIdentifier(), 'Failed to encode metadata as JSON');
            }
            $this->filesystem->dumpFile($integrityFile, $jsonContent);
            $this->logger->info("Stored integrity metadata for {$packageMetadata->getIdentifier()}");
        } catch (\Exception $e) {
            throw PackageException::integrityMetadataStoreFailed($packageMetadata->getIdentifier(), $e->getMessage());
        }
    }

    /**
     * Load integrity metadata for a cached package
     *
     * @param string $cacheDir Cache directory path
     *
     * @return array<string, mixed>|null Integrity metadata or null if not found
     *
     * @throws PackageException When metadata is corrupted
     */
    public function loadIntegrityMetadata(string $cacheDir): ?array
    {
        $integrityFile = $cacheDir . '/' . self::INTEGRITY_FILE;

        if (!$this->filesystem->exists($integrityFile)) {
            return null;
        }

        try {
            $content = file_get_contents($integrityFile);
            if ($content === false) {
                throw PackageException::integrityMetadataLoadFailed($cacheDir, 'Cannot read integrity file');
            }

            $metadata = json_decode($content, true);
            if ($metadata === null) {
                throw PackageException::integrityMetadataLoadFailed($cacheDir, 'Invalid JSON in integrity file');
            }

            return $metadata;
        } catch (\Exception $e) {
            throw PackageException::integrityMetadataLoadFailed($cacheDir, $e->getMessage());
        }
    }

    /**
     * Validate cache integrity for a package
     *
     * @param string          $cacheDir        Cache directory path
     * @param PackageMetadata $packageMetadata Package metadata
     * @param string          $packageFile     Path to the original package file
     *
     * @return bool True if cache is valid
     */
    public function validateCacheIntegrity(
        string $cacheDir,
        PackageMetadata $packageMetadata,
        string $packageFile
    ): bool {
        try {
            $metadata = $this->loadIntegrityMetadata($cacheDir);
            if ($metadata === null) {
                $this->logger->warning("No integrity metadata found for {$packageMetadata->getIdentifier()}");

                return false;
            }

            // Verify package identity
            if ($metadata['package_name']       !== $packageMetadata->getName()
                || $metadata['package_version'] !== $packageMetadata->getVersion()) {
                $this->logger->warning("Package identity mismatch in cache for {$packageMetadata->getIdentifier()}");

                return false;
            }

            // Verify package file checksum if available
            if ($this->filesystem->exists($packageFile)) {
                $algorithm = $metadata['algorithm'] ?? self::DEFAULT_HASH_ALGORITHM;
                if (!$this->verifyChecksum($packageFile, $metadata['checksum'], $algorithm)) {
                    $this->logger->warning("Package file checksum mismatch for {$packageMetadata->getIdentifier()}");

                    return false;
                }
            }

            // Verify cache directory structure
            $currentFileCount  = $this->countFiles($cacheDir);
            $expectedFileCount = $metadata['file_count'] ?? 0;

            if ($currentFileCount !== $expectedFileCount) {
                $this->logger->warning("File count mismatch for {$packageMetadata->getIdentifier()}. Expected: {$expectedFileCount}, Actual: {$currentFileCount}");

                return false;
            }

            $this->logger->debug("Cache integrity validation passed for {$packageMetadata->getIdentifier()}");

            return true;
        } catch (PackageException $e) {
            $this->logger->error("Cache integrity validation failed for {$packageMetadata->getIdentifier()}: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * Reconstruct PackageMetadata from cached integrity metadata
     *
     * @param string $cacheDir Cache directory path
     *
     * @return PackageMetadata|null Reconstructed package metadata or null if not available
     *
     * @throws PackageException When metadata is corrupted
     */
    public function reconstructPackageMetadata(string $cacheDir): ?PackageMetadata
    {
        $metadata = $this->loadIntegrityMetadata($cacheDir);
        if ($metadata === null) {
            return null;
        }

        // Check if we have the full package metadata stored
        if (!isset($metadata['package_metadata'])) {
            $this->logger->debug('Cache integrity file exists but does not contain full package metadata');

            return null;
        }

        try {
            $packageData = [
                'name'         => $metadata['package_name'],
                'version'      => $metadata['package_version'],
                'url'          => $metadata['package_metadata']['url']          ?? '',
                'description'  => $metadata['package_metadata']['description']  ?? '',
                'fhirVersions' => $metadata['package_metadata']['fhirVersions'] ?? [],
                'dependencies' => $metadata['package_metadata']['dependencies'] ?? [],
            ];

            return PackageMetadata::fromPackageData($packageData);
        } catch (\Exception $e) {
            $this->logger->warning("Failed to reconstruct package metadata from cache: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Clean up corrupted cache entries
     *
     * @param string $cacheDir Cache directory path
     *
     * @throws PackageException When cleanup fails
     */
    public function cleanupCorruptedCache(string $cacheDir): void
    {
        try {
            if ($this->filesystem->exists($cacheDir)) {
                $this->filesystem->remove($cacheDir);
                $this->logger->info("Cleaned up corrupted cache directory: {$cacheDir}");
            }
        } catch (\Exception $e) {
            throw PackageException::cacheCleanupFailed($cacheDir, $e->getMessage());
        }
    }

    /**
     * Count files in a directory recursively
     *
     * @param string $directory Directory path
     *
     * @return int Number of files
     */
    private function countFiles(string $directory): int
    {
        if (!$this->filesystem->exists($directory)) {
            return 0;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        $count = 0;
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() !== self::INTEGRITY_FILE) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * Calculate total size of a directory
     *
     * @param string $directory Directory path
     *
     * @return int Total size in bytes
     */
    private function calculateDirectorySize(string $directory): int
    {
        if (!$this->filesystem->exists($directory)) {
            return 0;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        $size = 0;
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() !== self::INTEGRITY_FILE) {
                $size += $file->getSize();
            }
        }

        return $size;
    }
}

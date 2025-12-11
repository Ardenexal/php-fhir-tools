<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Package;

use Ardenexal\FHIRTools\Exception\PackageException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

/**
 * Manages multiple FHIR version isolation
 *
 * This class provides comprehensive FHIR version isolation capabilities:
 *
 * - Separate cache directories for different FHIR versions
 * - Version-specific package management and loading
 * - Cross-version compatibility checking
 * - Version migration and upgrade support
 * - Isolated namespace management per FHIR version
 *
 * The isolation manager ensures that packages for different FHIR versions
 * (R4, R4B, R5, etc.) are kept separate to prevent conflicts and enable
 * simultaneous support for multiple FHIR versions.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Package
 */
class VersionIsolationManager
{
    /**
     * Supported FHIR versions
     */
    private const SUPPORTED_FHIR_VERSIONS = ['R4', 'R4B', 'R5', 'R6'];

    /**
     * Version directory separator
     */
    private const VERSION_SEPARATOR = '_';

    /**
     * Filesystem operations handler
     *
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * Logger for recording isolation operations
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Base cache directory
     *
     * @var string
     */
    private string $baseCacheDir;

    /**
     * Construct a new VersionIsolationManager
     *
     * @param string               $baseCacheDir Base cache directory
     * @param Filesystem           $filesystem   Filesystem operations handler
     * @param LoggerInterface|null $logger       Logger for recording operations (optional)
     */
    public function __construct(string $baseCacheDir, Filesystem $filesystem, ?LoggerInterface $logger = null)
    {
        $this->baseCacheDir = $baseCacheDir;
        $this->filesystem   = $filesystem;
        $this->logger       = $logger ?? new NullLogger();
    }

    /**
     * Get isolated cache directory for a specific FHIR version
     *
     * @param string $fhirVersion FHIR version (e.g., 'R4', 'R4B', 'R5')
     *
     * @return string Isolated cache directory path
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function getVersionCacheDir(string $fhirVersion): string
    {
        $this->validateFhirVersion($fhirVersion);

        $versionDir = Path::canonicalize($this->baseCacheDir . '/' . strtolower($fhirVersion));

        // Ensure the directory exists
        if (!$this->filesystem->exists($versionDir)) {
            $this->filesystem->mkdir($versionDir);
            $this->logger->debug("Created version cache directory: {$versionDir}");
        }

        return $versionDir;
    }

    /**
     * Get isolated package cache path for a specific package and FHIR version
     *
     * @param string $packageName Package name
     * @param string $version     Package version
     * @param string $fhirVersion FHIR version
     *
     * @return string Isolated package cache path
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function getPackageCachePath(string $packageName, string $version, string $fhirVersion): string
    {
        $versionCacheDir   = $this->getVersionCacheDir($fhirVersion);
        $packageIdentifier = $packageName . self::VERSION_SEPARATOR . $version;

        return Path::canonicalize($versionCacheDir . '/' . $packageIdentifier);
    }

    /**
     * Check if a package is cached for a specific FHIR version
     *
     * @param string $packageName Package name
     * @param string $version     Package version
     * @param string $fhirVersion FHIR version
     *
     * @return bool True if package is cached
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function isPackageCached(string $packageName, string $version, string $fhirVersion): bool
    {
        $packagePath = $this->getPackageCachePath($packageName, $version, $fhirVersion);

        return $this->filesystem->exists($packagePath);
    }

    /**
     * List all cached packages for a specific FHIR version
     *
     * @param string $fhirVersion FHIR version
     *
     * @return array<array{name: string, version: string, path: string}> Cached packages
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function listCachedPackages(string $fhirVersion): array
    {
        $versionCacheDir = $this->getVersionCacheDir($fhirVersion);

        if (!$this->filesystem->exists($versionCacheDir)) {
            return [];
        }

        $packages = [];
        $iterator = new \DirectoryIterator($versionCacheDir);

        foreach ($iterator as $item) {
            if ($item->isDot() || !$item->isDir()) {
                continue;
            }

            $dirName = $item->getFilename();
            $parts   = explode(self::VERSION_SEPARATOR, $dirName);

            if (count($parts) >= 2) {
                $version = array_pop($parts);
                $name    = implode(self::VERSION_SEPARATOR, $parts);

                $packages[] = [
                    'name'    => $name,
                    'version' => $version,
                    'path'    => $item->getPathname(),
                ];
            }
        }

        return $packages;
    }

    /**
     * Clean up cache for a specific FHIR version
     *
     * @param string $fhirVersion FHIR version
     *
     * @throws PackageException When cleanup fails
     */
    public function cleanVersionCache(string $fhirVersion): void
    {
        $versionCacheDir = $this->getVersionCacheDir($fhirVersion);

        try {
            if ($this->filesystem->exists($versionCacheDir)) {
                $this->filesystem->remove($versionCacheDir);
                $this->logger->info("Cleaned cache for FHIR version: {$fhirVersion}");
            }
        } catch (\Exception $e) {
            throw PackageException::cacheCleanupFailed($versionCacheDir, $e->getMessage());
        }
    }

    /**
     * Migrate packages from one FHIR version to another
     *
     * @param string $fromVersion Source FHIR version
     * @param string $toVersion   Target FHIR version
     * @param bool   $copyOnly    If true, copy instead of move
     *
     * @return int Number of packages migrated
     *
     * @throws PackageException When migration fails
     */
    public function migratePackages(string $fromVersion, string $toVersion, bool $copyOnly = false): int
    {
        $this->validateFhirVersion($fromVersion);
        $this->validateFhirVersion($toVersion);

        $fromDir = $this->getVersionCacheDir($fromVersion);
        $toDir   = $this->getVersionCacheDir($toVersion);

        if (!$this->filesystem->exists($fromDir)) {
            return 0;
        }

        $packages      = $this->listCachedPackages($fromVersion);
        $migratedCount = 0;

        foreach ($packages as $package) {
            $sourcePath = $package['path'];
            $targetPath = $this->getPackageCachePath($package['name'], $package['version'], $toVersion);

            try {
                if ($copyOnly) {
                    $this->filesystem->mirror($sourcePath, $targetPath);
                } else {
                    $this->filesystem->rename($sourcePath, $targetPath);
                }

                ++$migratedCount;
                $this->logger->info("Migrated package {$package['name']}@{$package['version']} from {$fromVersion} to {$toVersion}");
            } catch (\Exception $e) {
                $this->logger->error("Failed to migrate package {$package['name']}@{$package['version']}: {$e->getMessage()}");
            }
        }

        return $migratedCount;
    }

    /**
     * Get cache statistics for all FHIR versions
     *
     * @return array<string, array{package_count: int, total_size: int, last_modified: string|null}> Statistics by FHIR version
     */
    public function getCacheStatistics(): array
    {
        $statistics = [];

        foreach (self::SUPPORTED_FHIR_VERSIONS as $version) {
            try {
                $versionCacheDir = $this->getVersionCacheDir($version);
                $packages        = $this->listCachedPackages($version);

                $statistics[$version] = [
                    'package_count'   => count($packages),
                    'total_size'      => $this->calculateDirectorySize($versionCacheDir),
                    'last_modified'   => $this->getLastModifiedTime($versionCacheDir),
                ];
            } catch (PackageException) {
                $statistics[$version] = [
                    'package_count'   => 0,
                    'total_size'      => 0,
                    'last_modified'   => null,
                ];
            }
        }

        return $statistics;
    }

    /**
     * Check cross-version compatibility for a package
     *
     * @param string $packageName Package name
     * @param string $version     Package version
     * @param string $fromVersion Source FHIR version
     * @param string $toVersion   Target FHIR version
     *
     * @return bool True if package is compatible across versions
     */
    public function checkCrossVersionCompatibility(
        string $packageName,
        string $version,
        string $fromVersion,
        string $toVersion
    ): bool {
        // This is a simplified compatibility check
        // In practice, you might want to check package metadata,
        // FHIR version requirements, etc.

        $majorVersions = [
            'R4'  => 4,
            'R4B' => 4,
            'R5'  => 5,
            'R6'  => 6,
        ];

        $fromMajor = $majorVersions[$fromVersion] ?? 0;
        $toMajor   = $majorVersions[$toVersion]   ?? 0;

        // Generally, packages are compatible within the same major version
        return $fromMajor === $toMajor;
    }

    /**
     * Validate FHIR version
     *
     * @param string $fhirVersion FHIR version to validate
     *
     * @throws PackageException When FHIR version is not supported
     */
    private function validateFhirVersion(string $fhirVersion): void
    {
        if (!in_array($fhirVersion, self::SUPPORTED_FHIR_VERSIONS, true)) {
            throw PackageException::unsupportedFhirVersion($fhirVersion, self::SUPPORTED_FHIR_VERSIONS);
        }
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

        $size     = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    /**
     * Get last modified time for a directory
     *
     * @param string $directory Directory path
     *
     * @return string|null Last modified time in ISO format or null if not available
     */
    private function getLastModifiedTime(string $directory): ?string
    {
        if (!$this->filesystem->exists($directory)) {
            return null;
        }

        $lastModified = 0;
        $iterator     = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $lastModified = max($lastModified, $file->getMTime());
            }
        }

        return $lastModified > 0 ? date('c', $lastModified) : null;
    }
}

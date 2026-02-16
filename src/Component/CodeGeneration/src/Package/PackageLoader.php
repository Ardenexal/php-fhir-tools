<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Enhanced FHIR package loader with comprehensive management capabilities
 *
 * This class provides advanced FHIR package management including:
 *
 * - Semantic version resolution with range support
 * - Dependency chain resolution and conflict detection
 * - Package cache integrity verification with checksums
 * - Multiple FHIR version isolation
 * - Comprehensive package metadata management
 * - Resilient downloading with retry logic and error handling
 * - Local caching with integrity verification
 * - Package extraction and validation
 * - Loading of FHIR resources into the BuilderContext
 *
 * The enhanced PackageLoader integrates multiple specialized components
 * to provide enterprise-grade package management capabilities for FHIR
 * Implementation Guide packages.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools
 */
class PackageLoader
{
    /**
     * Default FHIR package registry URL
     */
    private const DEFAULT_REGISTRY = 'https://packages.fhir.org';

    /**
     * Version directory separator
     */
    private const VERSION_SEPARATOR = '_';

    /**
     * Retry handler for resilient network operations
     *
     * @var RetryHandler
     */
    private RetryHandler $retryHandler;

    /**
     * Logger for recording package operations and errors
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Semantic version resolver for version constraint handling
     *
     * @var SemanticVersionResolver
     */
    private SemanticVersionResolver $versionResolver;

    /**
     * Dependency resolver for dependency chain management
     *
     * @var DependencyResolver
     */
    private DependencyResolver $dependencyResolver;

    /**
     * Cache integrity manager for checksum verification
     *
     * @var CacheIntegrityManager
     */
    private CacheIntegrityManager $integrityManager;

    /**
     * Construct a new enhanced PackageLoader with comprehensive dependencies
     *
     * @param HttpClientInterface  $httpClient     HTTP client for downloading packages
     * @param string|null          $cacheDir       Directory for caching downloaded packages
     * @param BuilderContext       $contextBuilder Context for storing loaded FHIR resources
     * @param Filesystem           $filesystem     Filesystem operations handler
     * @param LoggerInterface|null $logger         Logger for recording operations (optional)
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%kernel.cache_dir%/.fhir')]
        private ?string $cacheDir,
        private BuilderContext $contextBuilder,
        private Filesystem $filesystem,
        ?LoggerInterface $logger = null,
    ) {
        $this->logger       = $logger ?? new NullLogger();
        $this->retryHandler = new RetryHandler($this->logger);

        // Initialize enhanced package management components
        $this->versionResolver    = new SemanticVersionResolver();
        $this->dependencyResolver = new DependencyResolver($this->versionResolver);
        $this->integrityManager   = new CacheIntegrityManager($this->filesystem, $this->logger);
    }

    /**
     * Try to load a package from cache without network access
     *
     * @param string      $packageName Package name to load
     * @param string|null $version     Exact version or version constraint
     *
     * @return PackageMetadata|null Package metadata if found in cache, null otherwise
     *
     * @throws PackageException When cache access fails
     */
    public function tryLoadFromCache(string $packageName, ?string $version = null): ?PackageMetadata
    {
        try {
            $cachedPackages = $this->listCachedPackages();

            // Filter packages by name
            $matchingPackages = array_filter($cachedPackages, function($pkg) use ($packageName) {
                return $pkg['name'] === $packageName;
            });

            if (empty($matchingPackages)) {
                $this->logger->debug("No cached packages found for {$packageName}");

                return null;
            }

            // If exact version specified, look for exact match
            if ($version !== null && !$this->versionResolver->isValidConstraint($version)) {
                foreach ($matchingPackages as $pkg) {
                    if ($pkg['version'] === $version) {
                        return $this->integrityManager->reconstructPackageMetadata($pkg['path']);
                    }
                }
                $this->logger->debug("Exact version {$version} not found in cache for {$packageName}");

                return null;
            }

            // If version constraint or null, find best matching version
            $availableVersions = array_column($matchingPackages, 'version');

            if ($version === null) {
                $bestVersion = $this->versionResolver->getLatestVersion($availableVersions);
            } else {
                $bestVersion = $this->versionResolver->resolveBestVersion($version, $availableVersions);
            }

            foreach ($matchingPackages as $pkg) {
                if ($pkg['version'] === $bestVersion) {
                    $packageMetadata = $this->integrityManager->reconstructPackageMetadata($pkg['path']);
                    if ($packageMetadata !== null) {
                        $this->logger->info("Loaded {$packageName}@{$bestVersion} from cache");
                    }

                    return $packageMetadata;
                }
            }

            return null;
        } catch (\Exception $e) {
            $this->logger->warning("Failed to load package from cache: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Install a FHIR package with enhanced dependency resolution and integrity verification
     *
     * @param string      $packageName Package name to install
     * @param string|null $version     Version constraint (e.g., "^1.0.0", "~1.2.0") or null for latest
     * @param string|null $registry    Package registry URL
     * @param bool        $resolveDeps Whether to resolve and install dependencies
     * @param bool        $offlineMode Whether to operate in offline mode (fail if not cached)
     *
     * @return PackageMetadata Installed package metadata
     *
     * @throws PackageException When installation fails
     */
    public function installPackage(
        string $packageName,
        ?string $version = null,
        ?string $registry = self::DEFAULT_REGISTRY,
        bool $resolveDeps = true,
        bool $offlineMode = false
    ): PackageMetadata {
        try {
            // Try to load from cache first (offline-first approach)
            $cachedMetadata = $this->tryLoadFromCache($packageName, $version);

            if ($cachedMetadata !== null) {
                // Verify cache integrity
                if ($this->isPackageCachedWithIntegrity($cachedMetadata)) {
                    $this->logger->info("Package {$packageName}@{$cachedMetadata->getVersion()} loaded from cache");

                    if ($resolveDeps) {
                        $this->resolveDependencies($cachedMetadata, $registry ?? self::DEFAULT_REGISTRY, $offlineMode);
                    }

                    $this->loadPackageStructureDefinitions($cachedMetadata);

                    return $cachedMetadata;
                }

                // Cache exists but integrity check failed
                $this->logger->warning("Cache integrity check failed for {$packageName}, will re-download");
            }

            // If offline mode and not in cache, fail early
            if ($offlineMode) {
                throw PackageException::packageNotAvailableOffline($packageName, $version);
            }

            // Resolve version if not specified (requires network)
            if ($version === null) {
                $availableVersions = $this->getAvailableVersions($packageName, $registry ?? self::DEFAULT_REGISTRY);
                $version           = $this->versionResolver->getLatestVersion($availableVersions);
            } elseif ($this->versionResolver->isValidConstraint($version)) {
                // Resolve version constraint
                $availableVersions = $this->getAvailableVersions($packageName, $registry ?? self::DEFAULT_REGISTRY);
                $version           = $this->versionResolver->resolveBestVersion($version, $availableVersions);
            }

            // Get package metadata from registry
            $packageMetadata = $this->getPackageMetadata($packageName, $version, $registry ?? self::DEFAULT_REGISTRY);

            // Check if package is already cached with integrity verification
            if ($this->isPackageCachedWithIntegrity($packageMetadata)) {
                $this->logger->info("Package {$packageName}@{$version} already cached and verified");

                if ($resolveDeps) {
                    $this->resolveDependencies($packageMetadata, $registry ?? self::DEFAULT_REGISTRY, $offlineMode);
                }

                $this->loadPackageStructureDefinitions($packageMetadata);

                return $packageMetadata;
            }

            // Download and install package
            $this->downloadAndInstallPackage($packageMetadata, $registry ?? self::DEFAULT_REGISTRY);

            // Resolve dependencies if requested
            if ($resolveDeps) {
                $this->resolveDependencies($packageMetadata, $registry ?? self::DEFAULT_REGISTRY, $offlineMode);
            }

            // Load package to context
            $this->loadPackageStructureDefinitions($packageMetadata);

            $this->logger->info("Successfully installed package: {$packageName}@{$version}");

            return $packageMetadata;
        } catch (PackageException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->logger->error("Failed to install package {$packageName}: {$e->getMessage()}");
            throw PackageException::invalidPackageResponse($packageName, $e->getMessage());
        }
    }

    /**
     * Check if package is cached with integrity verification
     *
     * @param PackageMetadata $packageMetadata Package metadata
     *
     * @return bool True if package is cached and verified
     */
    private function isPackageCachedWithIntegrity(PackageMetadata $packageMetadata): bool
    {
        if (!$this->isPackageCached($packageMetadata->getName(), $packageMetadata->getVersion())) {
            return false;
        }

        $cachePath   = $this->getPackageCachePath($packageMetadata->getName(), $packageMetadata->getVersion());
        $packageFile = $cachePath . '.tgz';

        return $this->integrityManager->validateCacheIntegrity($cachePath, $packageMetadata, $packageFile);
    }

    /**
     * Load package resources to BuilderContext with FHIR version isolation
     *
     * @param PackageMetadata $packageMetadata Package metadata
     *
     * @return array<string, mixed>
     *
     * @throws PackageException
     */
    public function loadPackageStructureDefinitions(PackageMetadata $packageMetadata): array
    {
        $packagePath = $this->getPackageCachePath(
            $packageMetadata->getName(),
            $packageMetadata->getVersion(),
        );

        if (!$this->filesystem->exists($packagePath)) {
            throw PackageException::packageNotFound($packageMetadata->getName(), $packageMetadata->getVersion());
        }

        $jsonFiles = (new Finder())->files()->in($packagePath)->filter(function(\SplFileInfo $file) {
            return $file->getExtension() === 'json';
        });

        $loadedCount          = 0;
        $structureDefinitions = [];
        foreach ($jsonFiles as $jsonFile) {
            $json = json_decode($jsonFile->getContents(), true);
            if (isset($json['resourceType'])) {
                switch ($json['resourceType']) {
                    case 'StructureDefinition':
                        $structureDefinitions[$json['url']] = $json;
                        ++$loadedCount;
                        break;
                    case 'ValueSet':
                    case 'CodeSystem':
                        $this->contextBuilder->addDefinition($json['url'], $json);
                        $structureDefinitions[$json['url']] = $json;
                        ++$loadedCount;
                        break;
                    default:
                        // No action needed for other resource types.
                        break;
                }
            }
        }

        $this->logger->debug("Loaded {$loadedCount} resources from package {$packageMetadata->getIdentifier()} to context");

        return  $structureDefinitions;
    }

    /**
     * Get available versions for a package from registry
     *
     * @param string $packageName Package name
     * @param string $registry    Registry URL
     *
     * @return array<string> Available versions
     *
     * @throws PackageException When versions cannot be retrieved
     */
    private function getAvailableVersions(string $packageName, string $registry): array
    {
        try {
            $packageVersions = $this->retryHandler->executeHttpWithRetry(
                fn () => $this->httpClient->request('GET', "$registry/$packageName"),
            );

            $versionsData = $packageVersions->toArray();

            if (!isset($versionsData['versions']) || !is_array($versionsData['versions'])) {
                throw PackageException::invalidPackageResponse($packageName, 'Invalid versions response format');
            }

            return array_keys($versionsData['versions']);
        } catch (ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw PackageException::packageNotFound($packageName);
        }
    }

    /**
     * Get package metadata from registry
     *
     * @param string $packageName Package name
     * @param string $version     Package version
     * @param string $registry    Registry URL
     *
     * @return PackageMetadata Package metadata
     *
     * @throws PackageException When metadata cannot be retrieved
     */
    private function getPackageMetadata(string $packageName, string $version, string $registry): PackageMetadata
    {
        try {
            // Get package versions to find the correct download URL
            $versionsResponse = $this->retryHandler->executeHttpWithRetry(
                fn () => $this->httpClient->request('GET', "$registry/$packageName"),
            );

            $versionsData = $versionsResponse->toArray();

            if (!isset($versionsData['versions'][$version])) {
                throw PackageException::packageNotFound($packageName, $version);
            }

            $packageData = $versionsData['versions'][$version];

            // Extract the correct download URL from dist.tarball
            $downloadUrl = $packageData['dist']['tarball'] ?? "$registry/$packageName/$version/";

            // Add computed fields
            $packageData['url']     = $downloadUrl;
            $packageData['name']    = $packageName;
            $packageData['version'] = $version;

            // Ensure we have fhirVersions array
            if (isset($packageData['fhirVersion']) && !isset($packageData['fhirVersions'])) {
                $packageData['fhirVersions'] = [$packageData['fhirVersion']];
            }

            return PackageMetadata::fromPackageData($packageData);
        } catch (ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw PackageException::packageNotFound($packageName, $version);
        }
    }

    /**
     * Download and install package with integrity verification
     *
     * @param PackageMetadata $packageMetadata Package metadata
     * @param string          $registry        Registry URL
     *
     * @throws PackageException When download or installation fails
     */
    private function downloadAndInstallPackage(PackageMetadata $packageMetadata, string $registry): void
    {
        $packageName = $packageMetadata->getName();
        $version     = $packageMetadata->getVersion();
        $url         = $packageMetadata->getUrl();

        $extractPath    = $this->getPackageCachePath($packageName, $version);
        $packageZipPath = $extractPath . '.tgz';

        $this->retryHandler->executeHttpWithRetry(function() use ($url, $packageName, $version, $packageZipPath, $extractPath, $packageMetadata) {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() !== 200) {
                throw PackageException::downloadFailed($packageName, $url, (string) $response->getStatusCode());
            }

            $packageZip = $response->getContent();

            $this->retryHandler->executeFileWithRetry(function() use ($packageZipPath, $packageZip, $extractPath, $packageName, $version, $packageMetadata) {
                // Save package file
                $this->filesystem->dumpFile($packageZipPath, $packageZip);

                // Generate checksum
                $checksum = $this->integrityManager->generateChecksum($packageZipPath);

                try {
                    // Extract package
                    $archive = new \PharData($packageZipPath);
                    $this->filesystem->remove($extractPath);
                    $archive->extractTo($extractPath, null, true);

                    // Validate extracted paths to prevent zip-slip attacks
                    $this->validateExtractedPaths($extractPath);

                    // Store integrity metadata
                    $this->integrityManager->storeIntegrityMetadata($extractPath, $packageMetadata, $checksum);

                    $this->logger->info("Downloaded and extracted package {$packageName}@{$version}");
                } catch (\Exception $e) {
                    // Cleanup on failure
                    $this->filesystem->remove($extractPath);
                    $this->filesystem->remove($packageZipPath);
                    throw PackageException::extractionFailed($packageName, $version, $e->getMessage());
                }
            });
        });
    }

    /**
     * Resolve and install package dependencies
     *
     * @param PackageMetadata $packageMetadata Root package metadata
     * @param string          $registry        Registry URL
     * @param bool            $offlineMode     Whether to operate in offline mode
     *
     * @throws PackageException When dependency resolution fails
     */
    private function resolveDependencies(PackageMetadata $packageMetadata, string $registry, bool $offlineMode = false): void
    {
        if (!$packageMetadata->hasDependencies()) {
            return;
        }

        $this->logger->info("Resolving dependencies for {$packageMetadata->getIdentifier()}");

        // Get available packages (simplified - in practice you'd query the registry)
        $availablePackages = [$packageMetadata->getName() => $packageMetadata];

        // For each dependency, get its metadata
        foreach ($packageMetadata->getDependencies() as $depName => $depConstraint) {
            try {
                $availableVersions           = $this->getAvailableVersions($depName, $registry);
                $bestVersion                 = $this->versionResolver->resolveBestVersion($depConstraint, $availableVersions);
                $depMetadata                 = $this->getPackageMetadata($depName, $bestVersion, $registry);
                $availablePackages[$depName] = $depMetadata;
            } catch (PackageException $e) {
                $this->logger->warning("Could not resolve dependency {$depName}: {$e->getMessage()}");
            }
        }

        try {
            $installationOrder = $this->dependencyResolver->resolveDependencies($packageMetadata, $availablePackages);

            // Install dependencies in order
            foreach ($installationOrder as $package) {
                if ($package->getName() !== $packageMetadata->getName()) {
                    $this->installPackage($package->getName(), $package->getVersion(), $registry, false, $offlineMode);
                }
            }

            $this->logger->info("Successfully resolved and installed dependencies for {$packageMetadata->getIdentifier()}");
        } catch (PackageException $e) {
            $this->logger->error("Dependency resolution failed for {$packageMetadata->getIdentifier()}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Validate package dependencies and detect conflicts
     *
     * @param array<PackageMetadata> $packages Packages to validate
     *
     * @return array<string, array{conflicts: array<string, string>, packages: array<string>}> Detected conflicts
     */
    public function validateDependencies(array $packages): array
    {
        return $this->dependencyResolver->detectConflicts($packages);
    }

    /**
     * Get isolated cache directory for a specific FHIR version
     *
     * @return string Isolated cache directory path
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function getVersionCacheDir(): string
    {
        $versionDir = Path::canonicalize($this->cacheDir . '/');

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
     *
     * @return string Isolated package cache path
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function getPackageCachePath(string $packageName, string $version): string
    {
        $versionCacheDir   = $this->getVersionCacheDir();
        $packageIdentifier = $packageName . self::VERSION_SEPARATOR . $version;

        return Path::canonicalize($versionCacheDir . '/' . $packageIdentifier);
    }

    /**
     * Check if a package is cached for a specific FHIR version
     *
     * @param string $packageName Package name
     * @param string $version     Package version
     *
     * @return bool True if package is cached
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function isPackageCached(string $packageName, string $version): bool
    {
        $packagePath = $this->getPackageCachePath($packageName, $version);

        return $this->filesystem->exists($packagePath);
    }

    /**
     * List all cached packages for a specific FHIR version
     *
     * @return array<array{name: string, version: string, path: string}> Cached packages
     *
     * @throws PackageException When FHIR version is not supported
     */
    public function listCachedPackages(): array
    {
        $versionCacheDir = $this->getVersionCacheDir();

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
     * @throws PackageException When cleanup fails
     */
    public function cleanVersionCache(): void
    {
        $versionCacheDir = $this->getVersionCacheDir();

        try {
            if ($this->filesystem->exists($versionCacheDir)) {
                $this->filesystem->remove($versionCacheDir);
                $this->logger->info('Cleaned cache');
            }
        } catch (\Exception $e) {
            throw PackageException::cacheCleanupFailed($versionCacheDir, $e->getMessage());
        }
    }

    /**
     * Get cache statistics for all FHIR versions
     *
     * @return array{package_count: int, total_size: int, last_modified: string|null} Statistics by FHIR version
     */
    public function getCacheStatistics(): array
    {
        $statistics = [];

        try {
            $versionCacheDir = $this->getVersionCacheDir();
            $packages        = $this->listCachedPackages();

            $statistics = [
                'package_count'   => count($packages),
                'total_size'      => $this->calculateDirectorySize($versionCacheDir),
                'last_modified'   => $this->getLastModifiedTime($versionCacheDir),
            ];
        } catch (PackageException) {
            $statistics = [
                'package_count'   => 0,
                'total_size'      => 0,
                'last_modified'   => null,
            ];
        }

        return $statistics;
    }

    /**
     * Validate that all extracted paths are within the extraction directory
     * and contain no symbolic links (zip-slip protection).
     *
     * @param string $extractPath The extraction target directory
     *
     * @throws PackageException If validation fails
     */
    private function validateExtractedPaths(string $extractPath): void
    {
        $realExtractPath = realpath($extractPath);
        if ($realExtractPath === false) {
            throw PackageException::extractionFailed('unknown', 'unknown', 'Invalid extraction path');
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($realExtractPath, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($iterator as $file) {
            if (is_link($file->getPathname())) {
                $this->filesystem->remove($extractPath);
                throw PackageException::extractionFailed('unknown', 'unknown', 'Archive contains symbolic links');
            }

            $realFilePath = realpath($file->getPathname());
            if ($realFilePath === false || !str_starts_with($realFilePath, $realExtractPath)) {
                $this->filesystem->remove($extractPath);
                throw PackageException::extractionFailed('unknown', 'unknown', 'Archive contains path traversal attempt');
            }
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

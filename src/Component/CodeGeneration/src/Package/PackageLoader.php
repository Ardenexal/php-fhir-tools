<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
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
     * Version isolation manager for FHIR version separation
     *
     * @var VersionIsolationManager
     */
    private VersionIsolationManager $isolationManager;

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
        $this->isolationManager   = new VersionIsolationManager($this->cacheDir ?? sys_get_temp_dir(), $this->filesystem, $this->logger);
    }

    /**
     * Install a FHIR package with enhanced dependency resolution and integrity verification
     *
     * @param string      $packageName Package name to install
     * @param string|null $version     Version constraint (e.g., "^1.0.0", "~1.2.0") or null for latest
     * @param string      $fhirVersion FHIR version for isolation (e.g., "R4", "R4B", "R5")
     * @param string|null $registry    Package registry URL
     * @param bool        $resolveDeps Whether to resolve and install dependencies
     *
     * @return PackageMetadata Installed package metadata
     *
     * @throws PackageException When installation fails
     */
    public function installPackage(
        string $packageName,
        ?string $version = null,
        string $fhirVersion = 'R4',
        ?string $registry = self::DEFAULT_REGISTRY,
        bool $resolveDeps = true
    ): PackageMetadata {
        try {
            // Resolve version if not specified
            if ($version === null) {
                $availableVersions = $this->getAvailableVersions($packageName, $registry ?? self::DEFAULT_REGISTRY);
                $version           = $this->versionResolver->getLatestVersion($availableVersions);
            } elseif ($this->versionResolver->isValidConstraint($version)) {
                // Resolve version constraint
                $availableVersions = $this->getAvailableVersions($packageName, $registry ?? self::DEFAULT_REGISTRY);
                $version           = $this->versionResolver->resolveBestVersion($version, $availableVersions);
            }

            // Check if package is already cached with integrity verification
            $packageMetadata = $this->getPackageMetadata($packageName, $version, $fhirVersion, $registry ?? self::DEFAULT_REGISTRY);

            if ($this->isPackageCachedWithIntegrity($packageMetadata, $fhirVersion)) {
                $this->logger->info("Package {$packageName}@{$version} already cached and verified for FHIR {$fhirVersion}");

                if ($resolveDeps) {
                    $this->resolveDependencies($packageMetadata, $fhirVersion, $registry ?? self::DEFAULT_REGISTRY);
                }

                $this->loadPackageToContext($packageMetadata, $fhirVersion);

                return $packageMetadata;
            }

            // Download and install package
            $this->downloadAndInstallPackage($packageMetadata, $fhirVersion, $registry ?? self::DEFAULT_REGISTRY);

            // Resolve dependencies if requested
            if ($resolveDeps) {
                $this->resolveDependencies($packageMetadata, $fhirVersion, $registry ?? self::DEFAULT_REGISTRY);
            }

            // Load package to context
            $this->loadPackageToContext($packageMetadata, $fhirVersion);

            $this->logger->info("Successfully installed package: {$packageName}@{$version} for FHIR {$fhirVersion}");

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
     * @param string          $fhirVersion     FHIR version
     *
     * @return bool True if package is cached and verified
     */
    private function isPackageCachedWithIntegrity(PackageMetadata $packageMetadata, string $fhirVersion): bool
    {
        if (!$this->isolationManager->isPackageCached($packageMetadata->getName(), $packageMetadata->getVersion(), $fhirVersion)) {
            return false;
        }

        $cachePath   = $this->isolationManager->getPackageCachePath($packageMetadata->getName(), $packageMetadata->getVersion(), $fhirVersion);
        $packageFile = $cachePath . '.tgz';

        return $this->integrityManager->validateCacheIntegrity($cachePath, $packageMetadata, $packageFile);
    }

    /**
     * Load package resources to BuilderContext with FHIR version isolation
     *
     * @param PackageMetadata $packageMetadata Package metadata
     * @param string          $fhirVersion     FHIR version
     */
    public function loadPackageToContext(PackageMetadata $packageMetadata, string $fhirVersion): void
    {
        $packagePath = $this->isolationManager->getPackageCachePath(
            $packageMetadata->getName(),
            $packageMetadata->getVersion(),
            $fhirVersion,
        );

        if (!$this->filesystem->exists($packagePath)) {
            throw PackageException::packageNotFound($packageMetadata->getName(), $packageMetadata->getVersion());
        }

        $jsonFiles = (new Finder())->files()->in($packagePath)->filter(function(\SplFileInfo $file) {
            return $file->getExtension() === 'json';
        });

        $loadedCount = 0;
        foreach ($jsonFiles as $jsonFile) {
            $json = json_decode($jsonFile->getContents(), true);
            if (isset($json['resourceType'])) {
                switch ($json['resourceType']) {
                    case 'StructureDefinition':
                        $this->contextBuilder->addDefinition($json['url'], $json);
                        ++$loadedCount;
                        break;
                    case 'ValueSet':
                    case 'CodeSystem':
                        $this->contextBuilder->addDefinition($json['url'], $json);
                        ++$loadedCount;
                        break;
                    default:
                        // No action needed for other resource types.
                        break;
                }
            }
        }

        $this->logger->debug("Loaded {$loadedCount} resources from package {$packageMetadata->getIdentifier()} to context");
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
     * @param string $fhirVersion FHIR version
     * @param string $registry    Registry URL
     *
     * @return PackageMetadata Package metadata
     *
     * @throws PackageException When metadata cannot be retrieved
     */
    private function getPackageMetadata(string $packageName, string $version, string $fhirVersion, string $registry): PackageMetadata
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
     * @param string          $fhirVersion     FHIR version
     * @param string          $registry        Registry URL
     *
     * @throws PackageException When download or installation fails
     */
    private function downloadAndInstallPackage(PackageMetadata $packageMetadata, string $fhirVersion, string $registry): void
    {
        $packageName = $packageMetadata->getName();
        $version     = $packageMetadata->getVersion();
        $url         = $packageMetadata->getUrl();

        $extractPath    = $this->isolationManager->getPackageCachePath($packageName, $version, $fhirVersion);
        $packageZipPath = $extractPath . '.tgz';

        $this->retryHandler->executeHttpWithRetry(function() use ($url, $packageName, $version, $packageZipPath, $extractPath, $packageMetadata, $fhirVersion) {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() !== 200) {
                throw PackageException::downloadFailed($packageName, $version, $response->getStatusCode());
            }

            $packageZip = $response->getContent();

            $this->retryHandler->executeFileWithRetry(function() use ($packageZipPath, $packageZip, $extractPath, $packageName, $version, $packageMetadata, $fhirVersion) {
                // Save package file
                $this->filesystem->dumpFile($packageZipPath, $packageZip);

                // Generate checksum
                $checksum = $this->integrityManager->generateChecksum($packageZipPath);

                try {
                    // Extract package
                    $archive = new \PharData($packageZipPath);
                    $this->filesystem->remove($extractPath);
                    $archive->extractTo($extractPath, null, true);

                    // Store integrity metadata
                    $this->integrityManager->storeIntegrityMetadata($extractPath, $packageMetadata, $checksum);

                    $this->logger->info("Downloaded and extracted package {$packageName}@{$version} for FHIR {$fhirVersion}");
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
     * @param string          $fhirVersion     FHIR version
     * @param string          $registry        Registry URL
     *
     * @throws PackageException When dependency resolution fails
     */
    private function resolveDependencies(PackageMetadata $packageMetadata, string $fhirVersion, string $registry): void
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
                $depMetadata                 = $this->getPackageMetadata($depName, $bestVersion, $fhirVersion, $registry);
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
                    $this->installPackage($package->getName(), $package->getVersion(), $fhirVersion, $registry, false);
                }
            }

            $this->logger->info("Successfully resolved and installed dependencies for {$packageMetadata->getIdentifier()}");
        } catch (PackageException $e) {
            $this->logger->error("Dependency resolution failed for {$packageMetadata->getIdentifier()}: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Get package cache statistics for all FHIR versions
     *
     * @return array<string, array{package_count: int, total_size: int, last_modified: string|null}> Statistics by FHIR version
     */
    public function getCacheStatistics(): array
    {
        return $this->isolationManager->getCacheStatistics();
    }

    /**
     * Clean cache for a specific FHIR version
     *
     * @param string $fhirVersion FHIR version to clean
     *
     * @throws PackageException When cleanup fails
     */
    public function cleanVersionCache(string $fhirVersion): void
    {
        $this->isolationManager->cleanVersionCache($fhirVersion);
    }

    /**
     * List all cached packages for a specific FHIR version
     *
     * @param string $fhirVersion FHIR version
     *
     * @return array<array{name: string, version: string, path: string}> Cached packages
     */
    public function listCachedPackages(string $fhirVersion): array
    {
        return $this->isolationManager->listCachedPackages($fhirVersion);
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
        return $this->isolationManager->migratePackages($fromVersion, $toVersion, $copyOnly);
    }
}

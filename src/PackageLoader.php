<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools;

use Ardenexal\FHIRTools\Exception\PackageException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Handles loading and caching of FHIR Implementation Guide packages
 *
 * This class is responsible for downloading, extracting, and loading FHIR
 * Implementation Guide packages from registries. It provides:
 *
 * - Package discovery and version resolution from FHIR registries
 * - Resilient downloading with retry logic and error handling
 * - Local caching to avoid repeated downloads
 * - Package extraction and validation
 * - Loading of FHIR resources into the BuilderContext
 *
 * The PackageLoader integrates with the retry handler to provide robust
 * network operations and comprehensive error reporting for package-related
 * operations.
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
     * Construct a new PackageLoader with required dependencies
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
    }

    /**
     * @param string      $packageName
     * @param string|null $version
     * @param string|null $registry
     *
     * @return array{
     *     name: string,
     *     version: string,
     *     fhirVersions: list<string>,
     *     url: string,
     *     description: string,
     *     author: string,
     *     license: string,
     *     dependencies: array<string, string>,
     *     title: string,
     * }
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function installPackage(string $packageName, ?string $version, ?string $registry = self::DEFAULT_REGISTRY): array
    {
        try {
            if ($version === null) {
                $version = $this->retryHandler->executeHttpWithRetry(
                    fn () => $this->resolveLatestVersion($packageName, $registry ?? self::DEFAULT_REGISTRY),
                );
            }

            $url            = "$registry/$packageName/$version/";
            $extractPath    = $this->cacheDir . '/' . $packageName . '-' . $version;
            $packageZipPath = $extractPath . '.tgz';

            if (!$this->checkCache($packageName, $version)) {
                $this->downloadAndExtractPackage($url, $packageName, $version, $packageZipPath, $extractPath);
            }

            $this->loadPackageToContext($packageName, $version);
            $packageJson = Path::canonicalize($extractPath . '/package/package.json');

            if (!$this->filesystem->exists($packageJson)) {
                throw PackageException::invalidPackageStructure($packageName, 'package.json not found');
            }

            $packageData = json_decode($this->filesystem->readFile($packageJson), true);
            if ($packageData === null) {
                throw PackageException::invalidPackageStructure($packageName, 'Invalid package.json format');
            }

            $this->logger->info("Successfully installed package: {$packageName} version {$version}");

            return $packageData;
        } catch (PackageException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->logger->error("Failed to install package {$packageName}: {$e->getMessage()}");
            throw PackageException::invalidPackageResponse($packageName, $e->getMessage());
        }
    }

    private function checkCache(string $packageName, string $version): bool
    {
        $cachePath = $this->cacheDir . '/' . $packageName . '-' . $version;
        if ($this->filesystem->exists($cachePath)) {
            return true;
        }

        return false;
    }

    public function loadPackageToContext(string $packageName, ?string $version): void
    {
        $packagePath = $this->cacheDir . '/' . $packageName . '-' . $version;
        $jsonFiles   = (new Finder())->files()->in($packagePath)->filter(function(\SplFileInfo $file) {
            return $file->getExtension() === 'json';
        });

        foreach ($jsonFiles as $jsonFile) {
            $json = json_decode($jsonFile->getContents(), true);
            if (isset($json['resourceType'])) {
                switch ($json['resourceType']) {
                    case 'StructureDefinition':
                        $this->contextBuilder->addDefinition($json['url'], $json);
                        break;
                    case 'ValueSet':
                    case 'CodeSystem':
                        $this->contextBuilder->addDefinition($json['url'], $json);
                        break;
                    default:
                        // No action needed for other resource types.
                        break;
                }
            }
        }
    }

    /**
     * Resolve the latest version of a package
     *
     * @param string $packageName
     * @param string $registry
     *
     * @return string
     *
     * @throws PackageException
     */
    private function resolveLatestVersion(string $packageName, string $registry): string
    {
        try {
            $packageVersions = $this->httpClient->request('GET', "$registry/$packageName");
            $versionsData    = $packageVersions->toArray();

            if (!isset($versionsData['versions']) || !is_array($versionsData['versions'])) {
                throw PackageException::invalidPackageResponse($packageName, 'Invalid versions response format');
            }

            $versionKeys = array_keys($versionsData['versions']);
            $lastVersion = array_last($versionKeys);

            if ($lastVersion === null) {
                throw PackageException::packageNotFound($packageName);
            }

            return (string) $lastVersion;
        } catch (ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw PackageException::packageNotFound($packageName, null);
        }
    }

    /**
     * Download and extract package with retry logic
     *
     * @param string $url
     * @param string $packageName
     * @param string $version
     * @param string $packageZipPath
     * @param string $extractPath
     *
     * @throws PackageException
     */
    private function downloadAndExtractPackage(string $url, string $packageName, string $version, string $packageZipPath, string $extractPath): void
    {
        $this->retryHandler->executeHttpWithRetry(function() use ($url, $packageName, $version, $packageZipPath, $extractPath) {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() !== 200) {
                throw PackageException::downloadFailed($packageName, $version, $response->getStatusCode());
            }

            $packageZip = $response->getContent();

            $this->retryHandler->executeFileWithRetry(function() use ($packageZipPath, $packageZip, $extractPath, $packageName, $version) {
                $this->filesystem->dumpFile($packageZipPath, $packageZip);

                try {
                    $archive = new \PharData($packageZipPath);
                    $this->filesystem->remove($extractPath);
                    $archive->extractTo($extractPath, null, true);
                } catch (\Exception $e) {
                    throw PackageException::extractionFailed($packageName, $version, $e->getMessage());
                }
            });
        });
    }
}

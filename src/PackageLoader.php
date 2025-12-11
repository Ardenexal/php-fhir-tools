<?php

namespace Ardenexal\FHIRTools;

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
 * Class PackageLoader
 *
 * @package Ardenexal\FHIRTools
 */
class PackageLoader
{
    private const       DEFAULT_REGISTRY = 'https://packages.fhir.org';

    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%kernel.cache_dir%/.fhir')]
        private ?string $cacheDir,
        private BuilderContext $contextBuilder,
        private Filesystem $filesystem,
    ) {
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
        if ($version === null) {
            $packageVersions = $this->httpClient->request('GET', "$registry/$packageName");
            $packageVersions->toArray();
            $version = array_last(array_keys($packageVersions->toArray()['versions']));
        }
        $url            = "$registry/$packageName/$version/";
        $extractPath    = $this->cacheDir . '/' . $packageName . '-' . $version;
        $packageZipPath = $extractPath . '.tgz';
        if (!$this->checkCache($packageName, $version)) {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException("Failed to download package: $packageName version: $version");
            }

            $packageZip = $response->getContent();

            $this->filesystem->dumpFile($packageZipPath, $packageZip);

            $archive = new \PharData($packageZipPath);

            $this->filesystem->remove($extractPath);
            $archive->extractTo($extractPath, null, true);
        }

        $this->loadPackageToContext($packageName, $version);
        $packageJson = Path::canonicalize($extractPath . '/package/package.json');

        return json_decode($this->filesystem->readFile($packageJson), true);
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
}

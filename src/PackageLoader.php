<?php

namespace Ardenexal\FHIRTools;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class PackageLoader
 *
 * @package Ardenexal\FHIRTools
 */
class PackageLoader
{
    private const DEFAULT_REGISTRY = 'https://packages.fhir.org';

    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%kernel.cache_dir%/.fhir')]
        private ?string             $cacheDir,
        private Filesystem          $filesystem,
    ) {
    }

    public function loadPackage(string $packageName, ?string $version, ?string $registry = self::DEFAULT_REGISTRY): string
    {
        if ($version !== null) {
            $packageName .= '-' . $version;
        } else {
            $packageVersions = $this->httpClient->request('GET', "$registry/$packageName");
            $packageVersions->toArray();
            $version = array_last(array_keys($packageVersions->toArray()['versions']));
        }
        $url            = "$registry/$packageName/$version/";
        $extractPath = $this->cacheDir . '/' . $packageName . '-' . $version;
        $packageZipPath = $extractPath . '.tgz';
        if (!$this->checkCache($packageName, $version)) {
            $response = $this->httpClient->request('GET', $url);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException("Failed to download package: $packageName version: $version");
            }

            $packageZip = $response->getContent();

            $this->filesystem->dumpFile($packageZipPath, $packageZip);

            $archive     = new \PharData($packageZipPath);

            $this->filesystem->remove($extractPath);
            $archive->extractTo($extractPath, null, true);
        }


        return $extractPath;
    }

    private function checkCache(string $packageName, string $version): bool
    {
        $cachePath = $this->cacheDir . '/' . $packageName . '-' . $version;
        if ($this->filesystem->exists($cachePath)) {
            return true;
        }

        return false;
    }
}

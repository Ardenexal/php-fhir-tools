<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\CacheWarmer;

use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProviderInterface;
use Composer\Autoload\ClassLoader;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Pre-populates the PSR-6 metadata cache for all discovered FHIR model classes.
 *
 * Discovers classes via the Composer PSR-4 autoload map for the Models namespace,
 * then scans the corresponding directories for PHP files.
 */
final class FHIRMetadataCacheWarmer implements CacheWarmerInterface
{
    private const MODELS_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models\\';

    /** @deprecated Use PropertyMetadataProvider::cacheKey() directly */
    public const CACHE_KEY_PREFIX = 'fhir.property_metadata.';

    public function __construct(
        private readonly PropertyMetadataProviderInterface $metadataProvider,
        private readonly CacheItemPoolInterface $cache,
    ) {
    }

    public function isOptional(): bool
    {
        return true;
    }

    /** @return list<string> */
    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        $dirs = $this->discoverModelDirectories();

        foreach ($dirs as $dir) {
            $this->warmDirectory($dir);
        }

        return [];
    }

    /** @return list<string> */
    private function discoverModelDirectories(): array
    {
        foreach (spl_autoload_functions() as $fn) {
            if (is_array($fn) && $fn[0] instanceof ClassLoader) {
                $prefixes = $fn[0]->getPrefixesPsr4();

                return $prefixes[self::MODELS_NAMESPACE] ?? [];
            }
        }

        return [];
    }

    private function warmDirectory(string $rootDir): void
    {
        $finder = new Finder();
        $finder->files()->in($rootDir)->name('*.php');

        foreach ($finder as $file) {
            $class = $this->deriveClassName($file);

            if ($class === null || !class_exists($class)) {
                continue;
            }

            $metadata = $this->metadataProvider->getPropertyMetadata($class);

            if ($metadata === []) {
                continue;
            }

            $item = $this->cache->getItem(self::cacheKey($class));
            $item->set($metadata);
            $this->cache->saveDeferred($item);
        }

        $this->cache->commit();
    }

    private function deriveClassName(SplFileInfo $file): ?string
    {
        $content = file_get_contents($file->getRealPath());

        if ($content === false) {
            return null;
        }

        if (!preg_match('/^namespace\s+([\w\\\\]+)\s*;/m', $content, $nsMatch)) {
            return null;
        }

        if (!preg_match('/^(?:final\s+|abstract\s+|readonly\s+)*class\s+(\w+)/m', $content, $classMatch)) {
            return null;
        }

        return $nsMatch[1] . '\\' . $classMatch[1];
    }

    public static function cacheKey(string $className): string
    {
        return PropertyMetadataProvider::cacheKey($className);
    }
}

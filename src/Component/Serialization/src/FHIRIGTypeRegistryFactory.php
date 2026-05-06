<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRSliceDiscriminator;
use Symfony\Component\Finder\Finder;

/**
 * Runtime factory for FHIRIGTypeRegistry.
 *
 * Mirrors the scanning logic of FHIRIGRegistryCompilerPass for use outside
 * the Symfony container compile step (e.g. standalone apps, the AI Mate MCP
 * server, or createWithIG service factories).
 *
 * Scans two sources:
 *   1. A user-supplied IG output directory (optional) for extension, profile,
 *      and slice-discriminator class mappings.
 *   2. Base models Extension directories auto-detected from the installed
 *      Models component (same sentinel-class strategy as the compiler pass).
 *
 * Classes that are not autoloadable are silently skipped.
 *
 * @author Ardenexal
 */
final class FHIRIGTypeRegistryFactory
{
    /**
     * Known base FHIR versions and the sentinel DataType\Extension class used to locate
     * each version's Extension directory via reflection.
     *
     * @var array<string, class-string>
     */
    private const array BASE_VERSION_SENTINELS = [
        'R4'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\Extension',
        'R4B' => 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\Extension',
        'R5'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType\\Extension',
    ];

    /**
     * Build a populated FHIRIGTypeRegistry by scanning IG output and base model directories.
     *
     * @param string $igOutputDirectory Absolute path to the IG output directory (e.g. /app/src/FHIRIG).
     *                                  Pass an empty string to skip IG scanning.
     * @param string $igNamespace       PSR-4 namespace root for the IG output directory
     *                                  (e.g. 'App\FHIR\IG'). Pass an empty string to skip IG scanning.
     */
    public static function create(
        string $igOutputDirectory = '',
        string $igNamespace = '',
    ): FHIRIGTypeRegistry {
        $extensionMappings          = [];
        $profileMappings            = [];
        $sliceDiscriminatorMappings = [];

        // 1. Scan user IG output directory.
        if ($igOutputDirectory !== '' && $igNamespace !== '' && is_dir($igOutputDirectory)) {
            self::registerPsr4Autoloader($igOutputDirectory, $igNamespace);
            self::scanDirectory($igOutputDirectory, $igNamespace, $extensionMappings, $profileMappings, $sliceDiscriminatorMappings);
        }

        // 2. Scan base models Extension directories (auto-detected from sentinel classes).
        foreach (self::resolveBaseExtensionDirectories() as $dir => $ns) {
            self::scanDirectory($dir, $ns, $extensionMappings, $profileMappings, $sliceDiscriminatorMappings);
        }

        return new FHIRIGTypeRegistry($extensionMappings, $profileMappings, $sliceDiscriminatorMappings);
    }

    /**
     * Scan a directory for PHP files and populate URL→class mappings from
     * #[FHIRExtensionDefinition], #[FHIRProfile], and #[FHIRSliceDiscriminator] attributes.
     *
     * @param array<string, array<string, class-string>>                                                      $extensionMappings
     * @param array<string, class-string>                                                                     $profileMappings
     * @param array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceDiscriminatorMappings
     */
    private static function scanDirectory(
        string $directory,
        string $namespace,
        array &$extensionMappings,
        array &$profileMappings,
        array &$sliceDiscriminatorMappings,
    ): void {
        $finder = new Finder();
        $finder->files()->in($directory)->name('*.php')->sortByName();

        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();
            $classSuffix  = str_replace(['/', '\\', DIRECTORY_SEPARATOR], '\\', $relativePath);
            $classSuffix  = preg_replace('/\.php$/i', '', $classSuffix) ?? $classSuffix;
            $className    = rtrim($namespace, '\\') . '\\' . ltrim($classSuffix, '\\');

            if (!class_exists($className)) {
                continue;
            }

            $refl     = new \ReflectionClass($className);
            $extAttrs = $refl->getAttributes(FHIRExtensionDefinition::class);

            if (!empty($extAttrs)) {
                /** @var FHIRExtensionDefinition $attr */
                $attr = $extAttrs[0]->newInstance();
                if (!isset($extensionMappings[$attr->url][$attr->fhirVersion])) {
                    $extensionMappings[$attr->url][$attr->fhirVersion] = $className;
                }
            }

            $profAttrs = $refl->getAttributes(FHIRProfile::class);

            if (!empty($profAttrs)) {
                /** @var FHIRProfile $attr */
                $attr = $profAttrs[0]->newInstance();
                if (!array_key_exists($attr->profileUrl, $profileMappings)) {
                    $profileMappings[$attr->profileUrl] = $className;
                }
            }

            $discriminatorAttrs = $refl->getAttributes(FHIRSliceDiscriminator::class);

            if (!empty($discriminatorAttrs)) {
                $parentClass = $refl->getParentClass();
                if ($parentClass !== false) {
                    $baseTypeFqcn = $parentClass->getName();

                    foreach ($discriminatorAttrs as $discriminatorAttr) {
                        /** @var FHIRSliceDiscriminator $discriminator */
                        $discriminator = $discriminatorAttr->newInstance();
                        /** @var class-string $className */
                        $sliceDiscriminatorMappings[$baseTypeFqcn][] = [
                            'type'        => $discriminator->type,
                            'path'        => $discriminator->path,
                            'value'       => $discriminator->value,
                            'targetClass' => $className,
                        ];
                    }
                }
            }
        }
    }

    /**
     * Register a PSR-4 autoloader for the given directory/namespace pair so that
     * class_exists() and ReflectionClass work even when the directory is not in
     * the project's composer autoload map (e.g. demo app IG output scanned from
     * the root project's autoloader context).
     */
    private static function registerPsr4Autoloader(string $directory, string $namespace): void
    {
        $prefix = rtrim($namespace, '\\') . '\\';
        $base   = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR;

        spl_autoload_register(static function(string $class) use ($prefix, $base): void {
            if (!str_starts_with($class, $prefix)) {
                return;
            }

            $relative = substr($class, \strlen($prefix));
            $file     = $base . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';

            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**
     * Auto-detect base models Extension directories for each supported FHIR version.
     *
     * @return array<string, string> directory path → namespace root
     */
    public static function resolveBaseExtensionDirectories(): array
    {
        $dirs = [];

        foreach (self::BASE_VERSION_SENTINELS as $version => $sentinelClass) {
            if (!class_exists($sentinelClass)) {
                continue;
            }

            $sentinelFile = (new \ReflectionClass($sentinelClass))->getFileName();

            if ($sentinelFile === false) {
                continue;
            }

            $extensionDir = dirname(dirname($sentinelFile)) . DIRECTORY_SEPARATOR . 'Extension';

            if (!is_dir($extensionDir)) {
                continue;
            }

            $namespace           = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Extension";
            $dirs[$extensionDir] = $namespace;
        }

        return $dirs;
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

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
        bool $includeBaseProfiles = false,
        string $baseProfileVersion = '',
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

        // 3. Base-spec profile classes (bp, vitalsigns, bodyweight, …), opt-in only.
        //
        // Scoped to one FHIR version on purpose. A base-spec profile's canonical URL carries no
        // version — `…/StructureDefinition/vitalsigns` is the same string in R4, R4B and R5 — and
        // profileMappings is keyed by URL, so registering every version makes the three collide and
        // whichever was scanned first wins. An R5 document then resolves to an R4 profile class.
        //
        // Registering these changes what FHIRTypeResolver::resolveResourceType() returns for any
        // document whose meta.profile names one of them: the typed profile subclass rather than the
        // base resource class. That is the point — the profile's own #[FHIRProfileConstraint]s are
        // only visible to the validator when the instance *is* that subclass — but it is also why it
        // is opt-in. Callers that have not asked for it keep byte-for-byte identical resolution.
        if ($includeBaseProfiles) {
            foreach (self::resolveBaseProfileDirectories($baseProfileVersion) as $dir => $ns) {
                // Profile URL → class only. A profile class also carries #[FHIRSliceDiscriminator]s,
                // and letting those into the registry would change how complex types resolve for
                // every document, not just the ones naming a profile — which silently altered
                // unrelated primitive-format and unknown-element findings across the corpus when
                // this scan was first added. Discard the other two collections.
                $ignoredExtensions     = [];
                $ignoredDiscriminators = [];
                self::scanDirectory($dir, $ns, $ignoredExtensions, $profileMappings, $ignoredDiscriminators);
            }
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
            $classSuffix  = str_replace(['/', '\\'], '\\', $relativePath);
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

    /**
     * Base model Profile directories, keyed directory => PSR-4 namespace.
     *
     * Located the same way as the Extension directories: from each version's sentinel class, since
     * the Models component's path is an install detail rather than something to hardcode.
     *
     * @param string $onlyVersion Restrict to one of 'R4', 'R4B', 'R5'. Empty returns every version,
     *                            which is only safe when the caller can tell the collisions apart.
     *
     * @return array<string, string>
     */
    public static function resolveBaseProfileDirectories(string $onlyVersion = ''): array
    {
        $dirs = [];

        foreach (self::BASE_VERSION_SENTINELS as $version => $sentinelClass) {
            if ($onlyVersion !== '' && $version !== $onlyVersion) {
                continue;
            }

            if (!class_exists($sentinelClass)) {
                continue;
            }

            $sentinelFile = (new \ReflectionClass($sentinelClass))->getFileName();

            if ($sentinelFile === false) {
                continue;
            }

            $profileDir = dirname(dirname($sentinelFile)) . DIRECTORY_SEPARATOR . 'Profile';

            if (!is_dir($profileDir)) {
                continue;
            }

            $namespace         = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Profile";
            $dirs[$profileDir] = $namespace;
        }

        return $dirs;
    }
}

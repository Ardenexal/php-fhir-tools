<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRSliceDiscriminator;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

/**
 * Populates FHIRIGTypeRegistry at container compile time.
 *
 * Builds extension and profile URL → typed PHP class mappings from two sources:
 *
 * 1. **IG output directory** (`fhir.ig.output_directory`) — user-generated IG classes
 *    (e.g. AU Base extensions, US Core profiles). Classes are discovered via the configured
 *    `fhir.ig.namespace` combined with each file's relative path.
 *
 * 2. **Base models extension directories** — typed extension classes generated from base
 *    FHIR packages (e.g. `hl7.fhir.uv.extensions.r4`) and stored in the Models component
 *    under `Models/src/{R4,R4B,R5}/Extension/`. The directories are auto-detected at compile
 *    time via reflection on known sentinel DataType classes — no additional configuration
 *    is needed.
 *
 * Requires IG and Models classes to be autoloadable (run `composer dump-autoload` after
 * generating classes). Classes that cannot be loaded are silently skipped.
 *
 * @author Ardenexal
 */
class FHIRIGRegistryCompilerPass implements CompilerPassInterface
{
    /**
     * Known base FHIR versions and the sentinel DataType\Extension class used to locate
     * each version's Extension directory via reflection.
     *
     * The sentinel is the DataType\Extension class (not a namespace). Its file path is used
     * to compute the sibling Extension/ directory: DataType/Extension.php → ../Extension/.
     *
     * @var array<string, class-string>
     */
    private const array BASE_VERSION_SENTINELS = [
        'R4'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\Extension',
        'R4B' => 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\Extension',
        'R5'  => 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType\\Extension',
    ];

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(FHIRIGTypeRegistry::class)) {
            return;
        }

        $extensionMappings          = [];
        $profileMappings            = [];
        $sliceDiscriminatorMappings = [];

        // 1. Scan user IG output directory (IG-specific extensions and profiles).
        $outputDir = $container->getParameter('fhir.ig.output_directory');
        $namespace = $container->getParameter('fhir.ig.namespace');

        if (is_string($outputDir) && $outputDir !== '' && is_string($namespace) && $namespace !== '' && is_dir($outputDir)) {
            $this->scanDirectoryIntoMappings($outputDir, $namespace, $extensionMappings, $profileMappings, $sliceDiscriminatorMappings);
        }

        // 2. Scan base models Extension directories for each supported FHIR version.
        //    Base extension classes (e.g. PGenderIdentityExtension) live here and also
        //    carry #[FHIRExtensionDefinition] attributes that must be registered so the
        //    deserializer can resolve them to their typed classes.
        foreach ($this->resolveBaseExtensionDirectories() as $dir => $ns) {
            $this->scanDirectoryIntoMappings($dir, $ns, $extensionMappings, $profileMappings, $sliceDiscriminatorMappings);
        }

        $registryDef = $container->getDefinition(FHIRIGTypeRegistry::class);
        $registryDef->setArgument('$extensionMappings', $extensionMappings);
        $registryDef->setArgument('$profileMappings', $profileMappings);
        $registryDef->setArgument('$sliceDiscriminatorMappings', $sliceDiscriminatorMappings);
    }

    /**
     * Scan a directory for PHP files and populate URL→class mappings using
     * #[FHIRExtensionDefinition], #[FHIRProfile], and #[FHIRSliceDiscriminator] attributes.
     *
     * Slice discriminator data is stored as plain arrays (not objects) because the Symfony
     * container serializes arguments to PHP/XML during cache compilation and cannot dump
     * object instances. FHIRIGTypeRegistry hydrates them to SliceDiscriminator objects
     * in its constructor.
     *
     * @param array<string, class-string>                                                         $extensionMappings
     * @param array<string, class-string>                                                         $profileMappings
     * @param array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceDiscriminatorMappings
     */
    /**
     * @param array<string, class-string>                                                                      $extensionMappings
     * @param array<string, class-string>                                                                      $profileMappings
     * @param array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>>  $sliceDiscriminatorMappings
     */
    private function scanDirectoryIntoMappings(
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
                continue; // Autoloader not configured for this path — skip gracefully.
            }

            $refl     = new \ReflectionClass($className);
            $extAttrs = $refl->getAttributes(FHIRExtensionDefinition::class);

            if (!empty($extAttrs)) {
                /** @var FHIRExtensionDefinition $attr */
                $attr = $extAttrs[0]->newInstance();
                // First-wins: IG-specific classes (scanned first) take priority over base model
                // classes, and earlier FHIR versions (R4 < R4B < R5) take priority over later
                // ones when the same extension URL appears across multiple versions.
                if (!array_key_exists($attr->url, $extensionMappings)) {
                    $extensionMappings[$attr->url] = $className;
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

            // Scan for #[FHIRSliceDiscriminator] attributes (repeatable).
            // These are emitted by FHIRConstrainedComplexTypeGenerator on profile classes
            // that have fixed[x] or pattern[x] values in their StructureDefinition.
            $discriminatorAttrs = $refl->getAttributes(FHIRSliceDiscriminator::class);

            if (!empty($discriminatorAttrs)) {
                // Resolve the base type FQCN from the parent class chain.
                // The immediate parent of a constrained profile is either the base FHIR type
                // (e.g. Identifier) or another IG profile class that ultimately extends it.
                $parentClass = $refl->getParentClass();
                if ($parentClass !== false) {
                    $baseTypeFqcn = $parentClass->getName();

                    foreach ($discriminatorAttrs as $discriminatorAttr) {
                        /** @var FHIRSliceDiscriminator $discriminator */
                        $discriminator = $discriminatorAttr->newInstance();
                        // Store as a plain array — objects cannot be serialized by the
                        // Symfony container's XmlDumper. FHIRIGTypeRegistry hydrates these.
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
     * Auto-detect base models Extension directories for each supported FHIR version.
     *
     * Uses reflection on known sentinel DataType\Extension classes to find their source
     * files, then resolves the sibling Extension/ directory. This works in both the
     * monorepo and consumer apps where the Models component is installed via Composer.
     *
     * @return array<string, string> directory path → namespace root
     */
    private function resolveBaseExtensionDirectories(): array
    {
        $dirs = [];

        foreach (self::BASE_VERSION_SENTINELS as $version => $sentinelClass) {
            if (!class_exists($sentinelClass)) {
                continue; // Models for this version not installed — skip.
            }

            $sentinelFile = (new \ReflectionClass($sentinelClass))->getFileName();

            if ($sentinelFile === false) {
                continue;
            }

            // sentinel is in …/Models/src/{version}/DataType/Extension.php
            // Extension classes are in …/Models/src/{version}/Extension/
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

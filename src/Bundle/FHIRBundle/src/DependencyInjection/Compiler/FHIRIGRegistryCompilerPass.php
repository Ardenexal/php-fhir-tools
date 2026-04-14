<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

/**
 * Populates FHIRIGTypeRegistry at container compile time.
 *
 * When both `fhir.ig.output_directory` and `fhir.ig.namespace` are configured, this pass
 * scans the output directory for PHP files, derives their fully-qualified class name from
 * the namespace and the file's relative path, and reads #[FHIRExtensionDefinition] /
 * #[FHIRProfile] attributes via reflection to build the extension and profile maps.
 *
 * Requires the IG classes to be autoloadable (composer dump-autoload must have been run
 * after the IG directory and namespace were added to composer.json). Classes that cannot
 * be loaded are silently skipped.
 *
 * @author Ardenexal
 */
class FHIRIGRegistryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(FHIRIGTypeRegistry::class)) {
            return;
        }

        $outputDir  = $container->getParameter('fhir.ig.output_directory');
        $namespace  = $container->getParameter('fhir.ig.namespace');

        if (!is_string($outputDir) || $outputDir === '' || !is_string($namespace) || $namespace === '') {
            return;
        }

        if (!is_dir($outputDir)) {
            return;
        }

        $extensionMappings = [];
        $profileMappings   = [];

        $finder = new Finder();
        $finder->files()->in($outputDir)->name('*.php')->sortByName();

        foreach ($finder as $file) {
            // Derive FQCN from namespace + relative path, e.g.:
            //   namespace = "App\FHIR\IG"
            //   relative  = "R4/UsCore/Extension/PatientBirthPlaceExtension.php"
            //   class     = "App\FHIR\IG\R4\UsCore\Extension\PatientBirthPlaceExtension"
            $relativePath = $file->getRelativePathname();
            $classSuffix  = str_replace(['/', '\\', DIRECTORY_SEPARATOR], '\\', $relativePath);
            $classSuffix  = preg_replace('/\.php$/i', '', $classSuffix) ?? $classSuffix;
            $className    = rtrim($namespace, '\\') . '\\' . ltrim($classSuffix, '\\');

            if (!class_exists($className)) {
                continue; // Autoloader not configured for this path — skip gracefully.
            }

            $refl = new \ReflectionClass($className);

            $extAttrs = $refl->getAttributes(FHIRExtensionDefinition::class);
            if (!empty($extAttrs)) {
                /** @var FHIRExtensionDefinition $attr */
                $attr                            = $extAttrs[0]->newInstance();
                $extensionMappings[$attr->url]   = $className;
            }

            $profAttrs = $refl->getAttributes(FHIRProfile::class);
            if (!empty($profAttrs)) {
                /** @var FHIRProfile $attr */
                $attr                                  = $profAttrs[0]->newInstance();
                $profileMappings[$attr->profileUrl]    = $className;
            }
        }

        $registryDef = $container->getDefinition(FHIRIGTypeRegistry::class);
        $registryDef->setArgument('$extensionMappings', $extensionMappings);
        $registryDef->setArgument('$profileMappings', $profileMappings);
    }
}

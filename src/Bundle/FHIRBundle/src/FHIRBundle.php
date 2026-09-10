<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRIGRegistryCompilerPass;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRServicePass;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRVersionedSerializerPass;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\FHIRExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * FHIR Bundle for Symfony integration.
 *
 * This bundle provides FHIR code generation and serialization services
 * for Symfony applications.
 *
 * @author Ardenexal FHIRTools Team
 */
class FHIRBundle extends Bundle
{
    /**
     * Pass priority that gets FHIRVersionedSerializerPass in front of Symfony's SerializerPass.
     *
     * FrameworkBundle registers `SerializerPass` at `TYPE_BEFORE_OPTIMIZATION` priority 0, and is built
     * before the application's own bundles — so a pass registered here with the default priority runs
     * after the `serializer.normalizer` tags have already been collected, and everything it tags is
     * dropped on the floor. The definitions exist, `debug:container --tag=serializer.normalizer` lists
     * them, and the runtime `serializer` contains none of them.
     *
     * Any priority above 0 is enough; 100 matches the band Symfony's own earliest passes use.
     */
    private const int SERIALIZER_PASS_PRIORITY = 100;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new FHIRServicePass());
        $container->addCompilerPass(
            new FHIRVersionedSerializerPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            self::SERIALIZER_PASS_PRIORITY,
        );
        $container->addCompilerPass(new FHIRIGRegistryCompilerPass());
    }

    public function getContainerExtension(): FHIRExtension
    {
        return new FHIRExtension();
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRServicePass;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\FHIRExtension;
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
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new FHIRServicePass());
    }

    public function getContainerExtension(): FHIRExtension
    {
        return new FHIRExtension();
    }
}

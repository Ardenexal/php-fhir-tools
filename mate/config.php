<?php

use App\Mate\Factory\FHIRValidationServiceFactory;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function(ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // FHIRPath — zero-arg construction; default in-memory cache is fine for MCP usage.
    $services->set(FHIRPathService::class);

    // Serialization — use the IG-aware factory so that extension/profile/discriminator mappings
    // from the demo app's FHIRIG output directory are registered at startup.
    $services->set(FHIRSerializationService::class)
        ->factory([FHIRSerializationService::class, 'createWithIG'])
        ->args([
            dirname(__DIR__) . '/demo/src/FHIRIG',
            'App\\FHIR\\IG',
        ]);

    // Metadata extractor and its dependency, plus the interface alias for constructor injection.
    $services->set(PropertyMetadataProvider::class);
    $services->set(FHIRMetadataExtractor::class);
    $services->alias(FHIRMetadataExtractorInterface::class, FHIRMetadataExtractor::class);

    // Validation — bootstrap the Symfony Validator with all FHIR-specific constraint validators.
    $services->set(FHIRValidationService::class)
        ->factory([FHIRValidationServiceFactory::class, 'create'])
        ->args([service(FHIRPathService::class)]);
};

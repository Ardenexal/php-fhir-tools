<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\FHIRExtension;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Verifies that fhir.yaml validation.message_overrides entries are wired into
 * FHIRValidationMessageRegistry via method calls on the service definition.
 */
final class FHIRValidationMessageOverrideConfigTest extends TestCase
{
    private function buildContainer(array $config): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', '/tmp/test');
        $container->setParameter('kernel.cache_dir', '/tmp/test/cache');

        $extension = new FHIRExtension();
        $extension->load([$config], $container);

        return $container;
    }

    public function testNoOverridesWhenConfigEmpty(): void
    {
        $container = $this->buildContainer([]);

        $def    = $container->getDefinition(FHIRValidationMessageRegistry::class);
        $calls  = array_filter(
            $def->getMethodCalls(),
            static fn (array $call) => $call[0] === 'setOverride',
        );

        self::assertCount(0, array_values($calls));
    }

    public function testOverridesRegisteredAsMethodCallsOnRegistry(): void
    {
        $container = $this->buildContainer([
            'validation' => [
                'message_overrides' => [
                    'FHIRFixedValue' => 'Custom fixed message: {{ expected }}',
                    'FHIRRegex'      => 'Custom regex message',
                ],
            ],
        ]);

        $def   = $container->getDefinition(FHIRValidationMessageRegistry::class);
        $calls = array_values(array_filter(
            $def->getMethodCalls(),
            static fn (array $call) => $call[0] === 'setOverride',
        ));

        self::assertCount(2, $calls);

        $byKey = [];
        foreach ($calls as $call) {
            $byKey[$call[1][0]] = $call[1][1];
        }

        self::assertSame('Custom fixed message: {{ expected }}', $byKey['FHIRFixedValue']);
        self::assertSame('Custom regex message', $byKey['FHIRRegex']);
    }

    public function testSingleOverrideRegisteredCorrectly(): void
    {
        $container = $this->buildContainer([
            'validation' => [
                'message_overrides' => [
                    'FHIRPathInvariant' => 'Invariant failed: {{ key }}',
                ],
            ],
        ]);

        $def   = $container->getDefinition(FHIRValidationMessageRegistry::class);
        $calls = array_values(array_filter(
            $def->getMethodCalls(),
            static fn (array $call) => $call[0] === 'setOverride',
        ));

        self::assertCount(1, $calls);
        self::assertSame('FHIRPathInvariant', $calls[0][1][0]);
        self::assertSame('Invariant failed: {{ key }}', $calls[0][1][1]);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Proves the hand-written operation fixtures actually match the published OperationDefinitions.
 *
 * The fixtures exist to prove the mapper before the generator exists, which only works if they are
 * faithful. Transcribing ~40 parameters across two versions by hand is exactly the kind of task that
 * silently drops one, and a mapper proven against a wrong target proves nothing. So rather than
 * trusting the transcription, every parameter is diffed against the published definition: name,
 * `use`, `min`, `max`, `type`, and the nesting structure.
 *
 * This test is temporary in spirit — once the generator emits these classes, fidelity is the
 * generator's property and this becomes a regression net for the fixtures themselves.
 */
final class OperationFixtureFidelityTest extends TestCase
{
    private const string R4_NS = 'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4';

    private const string R5_NS = 'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5';

    /**
     * Every IN parameter in the definition appears on the Input class, with matching metadata.
     */
    #[DataProvider('versionProvider')]
    public function testInputClassMatchesTheDefinition(string $version, string $namespace): void
    {
        self::assertParametersMatch(
            self::definitionParameters($version, 'in'),
            self::classParameters($namespace . '\CodeSystemLookupInput'),
            sprintf('%s $lookup IN', strtoupper($version)),
        );
    }

    /**
     * Every OUT parameter appears on the Output class, with matching metadata.
     */
    #[DataProvider('versionProvider')]
    public function testOutputClassMatchesTheDefinition(string $version, string $namespace): void
    {
        self::assertParametersMatch(
            self::definitionParameters($version, 'out'),
            self::classParameters($namespace . '\CodeSystemLookupOutput'),
            sprintf('%s $lookup OUT', strtoupper($version)),
        );
    }

    /**
     * Nested `part[]` groups match too, followed through $partClass rather than assumed.
     */
    #[DataProvider('nestedGroupProvider')]
    public function testNestedPartGroupsMatchTheDefinition(string $version, string $namespace, string $path): void
    {
        $segments = explode('.', $path);

        $definitionNode = self::findParameter(self::allParameters($version), array_shift($segments), 'out');
        self::assertNotNull($definitionNode);

        $class = $namespace . '\CodeSystemLookupOutput';
        $attr  = self::classParameters($class)[$definitionNode['name']] ?? null;
        self::assertNotNull($attr);
        self::assertNotNull($attr->partClass, sprintf('"%s" should point at a part class.', $path));

        $class = $attr->partClass;

        foreach ($segments as $segment) {
            $definitionNode = self::findParameter($definitionNode['part'] ?? [], $segment, 'out');
            self::assertNotNull($definitionNode);

            $attr = self::classParameters($class)[$segment] ?? null;
            self::assertNotNull($attr);
            self::assertNotNull($attr->partClass);

            $class = $attr->partClass;
        }

        self::assertParametersMatch(
            array_values(array_filter(
                $definitionNode['part'] ?? [],
                static fn (array $p): bool => $p['use'] === 'out',
            )),
            self::classParameters($class),
            sprintf('%s $lookup %s', strtoupper($version), $path),
        );
    }

    /**
     * @return iterable<string, array{string, string, string}>
     */
    public static function nestedGroupProvider(): iterable
    {
        foreach (['r4' => self::R4_NS, 'r5' => self::R5_NS] as $version => $namespace) {
            yield strtoupper($version) . ' designation'          => [$version, $namespace, 'designation'];
            yield strtoupper($version) . ' property'             => [$version, $namespace, 'property'];
            yield strtoupper($version) . ' property.subproperty' => [$version, $namespace, 'property.subproperty'];
        }
    }

    /**
     * The polymorphic `value` parameters carry all seven allowed types at both nesting levels.
     */
    #[DataProvider('versionProvider')]
    public function testPolymorphicValueCarriesSevenVariantsAtBothLevels(string $version, string $namespace): void
    {
        $property    = $namespace . '\CodeSystemLookupOutput\Property';
        $subproperty = $namespace . '\CodeSystemLookupOutput\PropertySubproperty';

        foreach ([$property, $subproperty] as $class) {
            $value = self::classParameters($class)['value'];

            self::assertNotNull($value->variants, sprintf('%s::$value has no variants.', $class));
            self::assertCount(7, $value->variants, sprintf('%s::$value lost a variant.', $class));
            self::assertSame(
                ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],
                array_column($value->variants, 'fhirType'),
            );
        }
    }

    /**
     * The two `value` parameters differ in cardinality — `property.value` is optional, the nested one
     * is required. A path-keyed scheme keeps that distinction; a name-keyed one would lose it.
     */
    #[DataProvider('versionProvider')]
    public function testNestedValueIsRequiredWhereTheOuterOneIsNot(string $version, string $namespace): void
    {
        $outer  = self::classParameters($namespace . '\CodeSystemLookupOutput\Property')['value'];
        $nested = self::classParameters($namespace . '\CodeSystemLookupOutput\PropertySubproperty')['value'];

        self::assertFalse($outer->isRequired(), 'property.value is 0..1 in the definition.');
        self::assertTrue($nested->isRequired(), 'property.subproperty.value is 1..1 in the definition.');
    }

    /**
     * A parameter named for a PHP reserved word is readable, not merely declarable.
     *
     * `designation.use` is the case D3 warns about. PHP permits `use` as a property name, so the
     * class parses and every reflection-based assertion in this file passes without ever reading it.
     * The mapper will read it. Proven here rather than discovered there.
     */
    #[DataProvider('versionProvider')]
    public function testReservedWordParameterIsReadableNotJustDeclarable(string $version, string $namespace): void
    {
        $codingClass = sprintf('Ardenexal\FHIRTools\Component\Models\%s\DataType\Coding', strtoupper($version));
        $designation = $namespace . '\CodeSystemLookupOutput\Designation';

        // Coding's own `code` is a CodePrimitive, not a string — the models wrap primitives. That
        // distinction is the mapper's problem (see N13); an empty Coding is enough to prove access.
        $coding   = new $codingClass();
        $instance = new $designation(language: 'en', use: $coding, value: 'Rechtsherzkatheter');

        self::assertSame($coding, $instance->use, 'Direct access to a reserved-word property failed.');
        self::assertSame($coding, $instance?->use, 'Nullsafe access to a reserved-word property failed.');
        self::assertSame('Rechtsherzkatheter', $instance->value);

        $viaReflection = (new \ReflectionProperty($designation, 'use'))->getValue($instance);
        self::assertSame($coding, $viaReflection, 'Reflection read of a reserved-word property failed.');
    }

    /**
     * Variant phpTypes resolve to real classes — the check the Metadata tests cannot make.
     */
    #[DataProvider('versionProvider')]
    public function testVariantPhpTypesResolve(string $version, string $namespace): void
    {
        $variants = [
            ...self::classParameters($namespace . '\CodeSystemLookupOutput\Property')['value']->variants            ?? [],
            ...self::classParameters($namespace . '\CodeSystemLookupOutput\PropertySubproperty')['value']->variants ?? [],
        ];

        self::assertCount(14, $variants, 'Expected seven variants at each of the two nesting levels.');

        foreach ($variants as $variant) {
            if (in_array($variant['propertyKind'], ['complex', 'primitive'], true)) {
                self::assertTrue(
                    class_exists($variant['phpType']),
                    sprintf('Variant phpType "%s" does not resolve.', $variant['phpType']),
                );
                self::assertStringContainsString(
                    '\\' . strtoupper($version) . '\\',
                    $variant['phpType'],
                    'Variant points at the wrong FHIR version’s models.',
                );

                continue;
            }

            self::assertContains($variant['phpType'], ['bool', 'int', 'float', 'string']);
        }
    }

    /**
     * The holders carry the invocation levels the definitions declare — and they differ by version.
     *
     * R4 `$lookup` is type-level only; R5 adds instance-level. Copy-pasting the R4 holder to R5 would
     * pass every other test in this file.
     */
    #[DataProvider('versionProvider')]
    public function testHolderMatchesTheDefinitionInvocationLevels(string $version, string $namespace): void
    {
        $definition = self::definition($version);

        $attributes = (new \ReflectionClass($namespace . '\CodeSystemLookupOperation'))
            ->getAttributes(FhirOperation::class);
        self::assertCount(1, $attributes);

        $holder = $attributes[0]->newInstance();

        self::assertSame($definition['code'], $holder->code);
        self::assertSame($definition['url'], $holder->url);
        self::assertSame(
            strtoupper($version),
            $holder->version,
            'Holder version does not match the package it was transcribed from — a copy-paste giveaway.',
        );
        self::assertSame($definition['resource'], $holder->resource);
        self::assertSame($definition['instance'], $holder->instance, 'instance-level invocation drifted.');
        self::assertSame($definition['type'], $holder->type, 'type-level invocation drifted.');
        self::assertSame($definition['system'] ?? false, $holder->system, 'system-level invocation drifted.');
    }

    /**
     * The versions genuinely differ, so a copy-paste of one holder into the other is caught.
     */
    public function testR4AndR5HoldersDifferOnInstanceLevelInvocation(): void
    {
        $r4 = (new \ReflectionClass(self::R4_NS . '\CodeSystemLookupOperation'))
            ->getAttributes(FhirOperation::class)[0]->newInstance();
        $r5 = (new \ReflectionClass(self::R5_NS . '\CodeSystemLookupOperation'))
            ->getAttributes(FhirOperation::class)[0]->newInstance();

        self::assertFalse($r4->instance);
        self::assertTrue($r5->instance);
        self::assertSame($r4->outputShape, $r5->outputShape);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4' => ['r4', self::R4_NS];
        yield 'R5' => ['r5', self::R5_NS];
    }

    /**
     * Assert a definition's parameter list and a class's attributes describe the same thing.
     *
     * @param list<array<string, mixed>>            $expected
     * @param array<string, FhirOperationParameter> $actual   keyed by wire name
     */
    private static function assertParametersMatch(array $expected, array $actual, string $label): void
    {
        // Order is asserted, not just membership, and that is a deliberate contract rather than an
        // artifact of transcribing in order. `Parameters.parameter` is an ordered list on the wire,
        // so declaration order determines emitted order, and definition order is the only ordering
        // with an external authority behind it. M02's generator MUST emit properties in definition
        // order — if it sorts (required-first, alphabetically), this is the test that will fail, and
        // the generator is what should change.
        self::assertSame(
            array_column($expected, 'name'),
            array_keys($actual),
            sprintf(
                '%s parameters differ from the published definition in membership or order. '
                . 'Declaration order must follow the definition.',
                $label,
            ),
        );

        foreach ($expected as $definitionParameter) {
            $name      = $definitionParameter['name'];
            $attribute = $actual[$name];

            self::assertSame($definitionParameter['use'], $attribute->use, sprintf('%s "%s" use', $label, $name));
            self::assertSame($definitionParameter['min'], $attribute->min, sprintf('%s "%s" min', $label, $name));
            self::assertSame((string) $definitionParameter['max'], $attribute->max, sprintf('%s "%s" max', $label, $name));
            self::assertSame(
                $definitionParameter['type'] ?? null,
                $attribute->type,
                sprintf('%s "%s" type', $label, $name),
            );

            if (isset($definitionParameter['part'])) {
                self::assertNotNull(
                    $attribute->partClass,
                    sprintf('%s "%s" has parts in the definition but no partClass.', $label, $name),
                );
            }
        }
    }

    /**
     * @return array<string, FhirOperationParameter> keyed by wire name
     */
    private static function classParameters(string $class): array
    {
        $found = [];

        foreach ((new \ReflectionClass($class))->getProperties() as $property) {
            foreach ($property->getAttributes(FhirOperationParameter::class) as $attribute) {
                $instance                 = $attribute->newInstance();
                $found[$instance->name]   = $instance;
            }
        }

        return $found;
    }

    /**
     * @param list<array<string, mixed>> $parameters
     *
     * @return array<string, mixed>|null
     */
    private static function findParameter(array $parameters, string $name, string $use): ?array
    {
        foreach ($parameters as $parameter) {
            if ($parameter['name'] === $name && $parameter['use'] === $use) {
                return $parameter;
            }
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function definitionParameters(string $version, string $use): array
    {
        return array_values(array_filter(
            self::allParameters($version),
            static fn (array $p): bool => $p['use'] === $use,
        ));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function allParameters(string $version): array
    {
        /** @var list<array<string, mixed>> $parameters */
        $parameters = self::definition($version)['parameter'];

        return $parameters;
    }

    /**
     * @return array<string, mixed>
     */
    private static function definition(string $version): array
    {
        $file = sprintf('%s/../../Fixtures/OperationDefinitions/%s-CodeSystem-lookup.json', __DIR__, $version);

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Definition fixture %s is unreadable.', $file));

        /** @var array<string, mixed> $definition */
        $definition = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        return $definition;
    }
}

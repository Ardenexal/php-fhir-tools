<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIROperationGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Exercises the generator itself, which nothing else did.
 *
 * Before this file, `rg 'FHIROperationGenerator'` over the non-vendor tree returned four hits: the
 * class declaration, the command's `use`, the command's single `new`, and a docblock mention. The
 * 20+ other new test files all reflect over the 375 *committed* classes under
 * `Models/src/{version}/Operation/`, so they prove the artefact and never the producer — which is why
 * Codecov reported this file at 0.00% while the operation test suite looked thorough.
 *
 * That matters beyond a coverage number. Those gates read output that is already on disk, so they
 * cannot notice a generator regression until a human regenerates and reads the diff; and CI
 * re-derives R4B only (`pr.yml`: `fhir:generate --package=hl7.fhir.r4b.core …`). An R4- or R5-only
 * regression therefore merges green. This runs the generator directly, on all three versions, with no
 * FHIR package cache required — the committed `OperationDefinitions/` and `TypeIndex/` fixtures are
 * enough to build a context.
 *
 * The assertions are about the generator's *contract*, not a golden file: which classes it registers,
 * what it names them, where it puts them, and which inputs it refuses. A snapshot test over emitted
 * source would fail on every formatting change and tell you nothing about behaviour.
 */
final class FHIROperationGeneratorTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4' => ['R4'];
        yield 'R4B' => ['R4B'];
        yield 'R5' => ['R5'];
    }

    #[DataProvider('versionProvider')]
    public function testItGeneratesTheLookupHolderAndRegistersItsPayloads(string $version): void
    {
        $context   = self::contextFor($version);
        $generator = new FHIROperationGenerator();

        $holder = $generator->generate(self::lookupDefinition($version), $version, $context);

        self::assertSame('CodeSystemLookupOperation', $holder->getName());
        self::assertSame(
            sprintf('Ardenexal\FHIRTools\Component\Models\%s\Operation\CodeSystemLookup', $version),
            $holder->getNamespace()?->getName(),
            'The namespace nests per operation because PSR-4 has to agree with the file layout.',
        );

        // The IN and OUT payloads are registered through the context rather than returned, so the
        // command can write them; asserting on the context is asserting on the real contract.
        $registered = array_keys($context->getTypes());

        self::assertContains('CodeSystemLookupInput', self::classNames($context));
        self::assertContains('CodeSystemLookupOutput', self::classNames($context));
        self::assertNotSame([], $registered);
    }

    /**
     * The nested `part[]` group becomes its own class, flat and `use`-prefixed.
     *
     * `$lookup` declares `property` in both directions — an `in` parameter typed `code` and an `out`
     * backbone group. The flat `Out`-prefixed naming is what makes that collision impossible by
     * construction, so it is asserted rather than assumed.
     */
    #[DataProvider('versionProvider')]
    public function testNestedPartGroupsBecomeFlatUsePrefixedClasses(string $version): void
    {
        $context = self::contextFor($version);
        (new FHIROperationGenerator())->generate(self::lookupDefinition($version), $version, $context);

        $names = self::classNames($context);

        self::assertContains('CodeSystemLookupOutProperty', $names);
        self::assertNotContains(
            'CodeSystemLookupProperty',
            $names,
            'An un-prefixed name would collide with the `in` `property` parameter.',
        );
    }

    /**
     * `kind: query` is excluded, with a rationale recorded in the plan — pinned so it stays excluded.
     */
    public function testQueryDefinitionsAreRefused(): void
    {
        $generator = new FHIROperationGenerator();
        $query     = ['resourceType' => 'OperationDefinition', 'kind' => 'query', 'code' => 'x'];

        self::assertFalse($generator->canGenerate($query));

        $this->expectException(GenerationException::class);
        $generator->generate($query, 'R5', self::contextFor('R5'));
    }

    /**
     * A parameter-name collision inside one class is fatal, and the message names both sources.
     *
     * This is the branch the command's containment now catches (see
     * `BuildOperationsErrorContainmentTest`), so the generator side of that contract is pinned here.
     */
    public function testCollidingParameterNamesAreFatal(): void
    {
        $definition = [
            'resourceType' => 'OperationDefinition',
            'url'          => 'http://example.test/OperationDefinition/collide',
            'name'         => 'Collide',
            'kind'         => 'operation',
            'code'         => 'collide',
            'resource'     => ['Patient'],
            'system'       => false,
            'type'         => true,
            'instance'     => false,
            'parameter'    => [
                ['name' => 'two-words', 'use' => 'in', 'min' => 0, 'max' => '1', 'type' => 'string'],
                ['name' => 'twoWords', 'use' => 'in', 'min' => 0, 'max' => '1', 'type' => 'string'],
            ],
        ];

        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/twoWords/');

        (new FHIROperationGenerator())->generate($definition, 'R5', self::contextFor('R5'));
    }

    /**
     * Generation is deterministic: the same input twice yields identical emitted source.
     *
     * Non-deterministic ordering would mean every regen produced a spurious diff, which is the thing
     * that makes a real drift invisible in review.
     */
    #[DataProvider('versionProvider')]
    public function testGenerationIsDeterministic(string $version): void
    {
        $definition = self::lookupDefinition($version);

        $first  = (new FHIROperationGenerator())->generate($definition, $version, self::contextFor($version));
        $second = (new FHIROperationGenerator())->generate($definition, $version, self::contextFor($version));

        self::assertSame((string) $first, (string) $second);
    }

    /**
     * All three versions produce the same class names from the same operation.
     *
     * The one-generator-many-versions premise: the versions differ in the metadata the generator
     * reads, not in the names it derives.
     */
    public function testTheThreeVersionsAgreeOnClassNames(): void
    {
        $perVersion = [];

        foreach (['R4', 'R4B', 'R5'] as $version) {
            $context = self::contextFor($version);
            (new FHIROperationGenerator())->generate(self::lookupDefinition($version), $version, $context);

            $names = self::classNames($context);
            sort($names);
            $perVersion[$version] = $names;
        }

        // R5 adds a nested subproperty group that R4/R4B lack, so agreement is on the shared core
        // rather than on identity — asserted as a subset so the test says which way it may differ.
        foreach (['CodeSystemLookupInput', 'CodeSystemLookupOutput', 'CodeSystemLookupOutProperty'] as $expected) {
            foreach ($perVersion as $version => $names) {
                self::assertContains($expected, $names, sprintf('%s is missing %s', $version, $expected));
            }
        }
    }

    /**
     * @return list<string>
     */
    private static function classNames(BuilderContextInterface $context): array
    {
        $names = [];

        foreach ($context->getTypes() as $generated) {
            $names[] = $generated->class->getName() ?? '';
        }

        return $names;
    }

    /**
     * A context carrying the committed type index — no FHIR package cache needed.
     */
    private static function contextFor(string $version): BuilderContextInterface
    {
        $file = sprintf('%s/../../Fixtures/TypeIndex/%s-type-index.json', __DIR__, strtolower($version));

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Type index %s is unreadable.', $file));

        /** @var array<string, array<string, mixed>> $definitions */
        $definitions = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        $context = new BuilderContext();
        $context->loadDefinitions($definitions);

        return $context;
    }

    /**
     * @return array<string, mixed>
     */
    private static function lookupDefinition(string $version): array
    {
        $file = sprintf(
            '%s/../../Fixtures/OperationDefinitions/%s-CodeSystem-lookup.json',
            __DIR__,
            strtolower($version),
        );

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Definition %s is unreadable.', $file));

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}

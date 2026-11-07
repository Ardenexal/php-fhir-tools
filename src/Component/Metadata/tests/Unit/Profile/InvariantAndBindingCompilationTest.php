<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledAttribute;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * A differential's invariants and required or extensible bindings compile to path-aware profile
 * attributes, leaving out what the base type already enforces.
 */
#[CoversClass(StructureDefinitionCompiler::class)]
final class InvariantAndBindingCompilationTest extends TestCase
{
    private const string URL = 'http://example.org/fhir/StructureDefinition/obs';

    public function testInvariantsBecomeProfileInvariantsAtTheirElementPath(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compile(self::profile([
            ['id' => 'Observation', 'path' => 'Observation', 'constraint' => [self::invariant('obs-a', 'value.exists()')]],
            ['id' => 'Observation.component', 'path' => 'Observation.component', 'constraint' => [
                self::invariant('obs-b', 'code.exists()', 'warning'),
                self::invariant('obs-c', 'value.exists()') + ['extension' => [['url' => 'http://hl7.org/fhir/StructureDefinition/elementdefinition-bestpractice', 'valueBoolean' => true]]],
            ]],
            ['id' => 'Observation.effective[x]', 'path' => 'Observation.effective[x]', 'constraint' => [['key' => 'xpath-only', 'severity' => 'error', 'human' => 'x', 'xpath' => 'f:x']]],
        ]));

        self::assertSame([
            ['path' => '', 'key' => 'obs-a', 'severity' => 'error', 'expression' => 'value.exists()', 'human' => 'obs-a holds', 'groups' => [self::URL]],
            ['path' => 'component', 'key' => 'obs-b', 'severity' => 'warning', 'expression' => 'code.exists()', 'human' => 'obs-b holds', 'groups' => [self::URL]],
            ['path' => 'component', 'key' => 'obs-c', 'severity' => 'error', 'expression' => 'value.exists()', 'human' => 'obs-c holds', 'bestPractice' => true, 'groups' => [self::URL]],
        ], self::argumentsOf($compiled->attributes, FHIRProfileInvariant::class));
    }

    public function testInvariantsTheBaseTypeOrAnotherDefinitionDeclaresAreLeftOut(): void
    {
        $base = ['type' => 'Observation', 'snapshot' => ['element' => [
            ['id' => 'Observation', 'path' => 'Observation', 'constraint' => [['key' => 'obs-6']]],
        ]]];

        $compiled = (new StructureDefinitionCompiler())->compile(self::profile([
            ['id' => 'Observation', 'path' => 'Observation', 'constraint' => [
                self::invariant('obs-6', 'restated()'),
                self::invariant('parent-1', 'parent()') + ['source' => 'http://example.org/fhir/StructureDefinition/parent'],
                self::invariant('own-1', 'own()')       + ['source' => self::URL . '|1.0'],
            ]],
        ]), static fn (string $url): ?array => $url === 'http://hl7.org/fhir/StructureDefinition/Observation' ? $base : null);

        self::assertSame(['own-1'], array_column(self::argumentsOf($compiled->attributes, FHIRProfileInvariant::class), 'key'));
    }

    public function testRequiredAndExtensibleBindingsBecomeProfileBindings(): void
    {
        $base = ['type' => 'Observation', 'snapshot' => ['element' => [
            ['id' => 'Observation.status', 'path' => 'Observation.status', 'binding' => ['strength' => 'required', 'valueSet' => 'http://hl7.org/fhir/ValueSet/observation-status|4.0.1']],
            ['id' => 'Observation.code', 'path' => 'Observation.code', 'binding' => ['strength' => 'example', 'valueSet' => 'http://hl7.org/fhir/ValueSet/observation-codes']],
        ]]];

        $compiled = (new StructureDefinitionCompiler())->compile(self::profile([
            ['id' => 'Observation.status', 'path' => 'Observation.status', 'binding' => ['strength' => 'required', 'valueSet' => 'http://hl7.org/fhir/ValueSet/observation-status|4.0.1']],
            ['id' => 'Observation.code', 'path' => 'Observation.code', 'binding' => ['strength' => 'extensible', 'valueSet' => 'http://example.org/ValueSet/codes']],
            ['id' => 'Observation.component.value[x]', 'path' => 'Observation.component.value[x]', 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/units|1.0']],
            ['id' => 'Observation.interpretation', 'path' => 'Observation.interpretation', 'binding' => ['strength' => 'preferred', 'valueSet' => 'http://example.org/ValueSet/interpretation']],
            ['id' => 'Observation.method', 'path' => 'Observation.method', 'binding' => ['strength' => 'example', 'valueSet' => 'http://example.org/ValueSet/method']],
        ]), static fn (string $url): ?array => $url === 'http://hl7.org/fhir/StructureDefinition/Observation' ? $base : null);

        // The status binding restates the base type's own, so the base model already checks it
        self::assertSame([
            ['path' => 'code', 'valueSetUrl' => 'http://example.org/ValueSet/codes', 'strength' => 'extensible', 'groups' => [self::URL]],
            ['path' => 'component.value[x]', 'valueSetUrl' => 'http://example.org/ValueSet/units|1.0', 'strength' => 'required', 'groups' => [self::URL]],
        ], self::argumentsOf($compiled->attributes, FHIRProfileBinding::class));
    }

    public function testRulesInsideASliceAreReportedNotEnforced(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compile(self::profile([
            ['id' => 'Observation.component', 'path' => 'Observation.component', 'slicing' => ['discriminator' => [['type' => 'pattern', 'path' => 'code']], 'rules' => 'open']],
            ['id' => 'Observation.component:systolic', 'path' => 'Observation.component', 'sliceName' => 'systolic', 'constraint' => [self::invariant('sys-1', 'value.exists()')]],
            ['id' => 'Observation.component:systolic.code', 'path' => 'Observation.component.code', 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/systolic']],
        ]));

        self::assertSame([], self::argumentsOf($compiled->attributes, FHIRProfileInvariant::class));
        self::assertSame([], self::argumentsOf($compiled->attributes, FHIRProfileBinding::class));

        $messages = array_map(static fn ($d): string => $d->message, $compiled->diagnostics);
        self::assertContains('Profile ' . self::URL . ': invariant sys-1 on Observation.component:systolic is inside a slice; it is not enforced', $messages);
        self::assertContains('Profile ' . self::URL . ': the required binding to http://example.org/ValueSet/systolic on Observation.component:systolic.code is inside a slice; it is not enforced', $messages);
    }

    public function testAnInlinedDataTypeProfileBringsItsInvariantUnderTheElement(): void
    {
        $quantity = [
            'url'            => 'http://example.org/fhir/StructureDefinition/NoComparator',
            'type'           => 'Quantity',
            'kind'           => 'complex-type',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Quantity',
            'snapshot'       => ['element' => [['id' => 'Quantity', 'path' => 'Quantity']]],
            'differential'   => ['element' => [['id' => 'Quantity', 'path' => 'Quantity', 'constraint' => [self::invariant('nc-1', 'comparator.empty()')]]]],
        ];

        $compiled = (new StructureDefinitionCompiler())->compile(
            self::profile([['id' => 'Observation.valueQuantity', 'path' => 'Observation.valueQuantity', 'type' => [['code' => 'Quantity', 'profile' => [$quantity['url']]]]]]),
            static fn (string $url): ?array => $url === $quantity['url'] ? $quantity : null,
        );

        self::assertSame(
            [['path' => 'valueQuantity', 'key' => 'nc-1', 'severity' => 'error', 'expression' => 'comparator.empty()', 'human' => 'nc-1 holds', 'groups' => [self::URL]]],
            self::argumentsOf($compiled->attributes, FHIRProfileInvariant::class),
        );
    }

    public function testAnExtensionsInvariantsAndBindingsCarryNoGroup(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compileExtension([
            'url'          => 'http://example.org/fhir/StructureDefinition/ext',
            'type'         => 'Extension',
            'derivation'   => 'constraint',
            'snapshot'     => ['element' => [['id' => 'Extension', 'path' => 'Extension']]],
            'differential' => ['element' => [
                ['id' => 'Extension', 'path' => 'Extension', 'constraint' => [self::invariant('ext-a', 'value.exists()')]],
                ['id' => 'Extension.extension:part.value[x]', 'path' => 'Extension.extension.value[x]', 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/part']],
                ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/v']],
            ]],
        ]);

        self::assertEquals([
            new CompiledAttribute(FHIRProfileInvariant::class, ['path' => '', 'key' => 'ext-a', 'severity' => 'error', 'expression' => 'value.exists()', 'human' => 'ext-a holds']),
            new CompiledAttribute(FHIRProfileBinding::class, ['path' => 'value[x]', 'valueSetUrl' => 'http://example.org/ValueSet/v', 'strength' => 'required']),
        ], $compiled->constraints);

        // The sub-extension's binding sits inside a slice, so it is reported instead
        self::assertSame(
            ['Profile http://example.org/fhir/StructureDefinition/ext: the required binding to http://example.org/ValueSet/part on Extension.extension:part.value[x] is inside a slice; it is not enforced'],
            array_map(static fn ($d): string => $d->message, $compiled->diagnostics),
        );
    }

    /**
     * @param list<CompiledAttribute> $attributes
     * @param class-string            $class
     *
     * @return list<array<string, mixed>>
     */
    private static function argumentsOf(array $attributes, string $class): array
    {
        return array_values(array_map(
            static fn (CompiledAttribute $a): array => $a->arguments,
            array_filter($attributes, static fn (CompiledAttribute $a): bool => $a->class === $class),
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private static function invariant(string $key, string $expression, string $severity = 'error'): array
    {
        return ['key' => $key, 'severity' => $severity, 'human' => "{$key} holds", 'expression' => $expression];
    }

    /**
     * @param list<array<string, mixed>> $differential
     *
     * @return array<string, mixed>
     */
    private static function profile(array $differential): array
    {
        return [
            'url'          => self::URL,
            'type'         => 'Observation',
            'kind'         => 'resource',
            'derivation'   => 'constraint',
            'snapshot'     => ['element' => [['id' => 'Observation', 'path' => 'Observation']]],
            'differential' => ['element' => $differential],
        ];
    }
}

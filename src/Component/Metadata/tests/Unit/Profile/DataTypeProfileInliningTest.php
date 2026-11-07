<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledAttribute;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * A data-type profile named in an element's `type.profile` is compiled into the referencing
 * profile: its rules move under the element's path and into the referencing profile's group.
 */
#[CoversClass(StructureDefinitionCompiler::class)]
final class DataTypeProfileInliningTest extends TestCase
{
    private const string BASE = 'http://example.org/fhir/StructureDefinition/';

    private const string OBSERVATION = self::BASE . 'obs';

    public function testRulesMoveUnderTheElementPathAndIntoTheReferencingGroup(): void
    {
        $compiled = self::compile(
            [self::element('Observation.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'ident|1.0']])],
            [self::BASE . 'ident' => self::identifierProfile()],
        );

        self::assertSame([], $compiled->diagnostics);
        self::assertSame(
            [
                [FHIRProfileConstraint::class, 'identifier.system', [self::OBSERVATION]],
                [FHIRSlicingRules::class, 'identifier.type.coding', [self::OBSERVATION]],
                [FHIRSliceConstraint::class, 'identifier.type.coding', [self::OBSERVATION]],
            ],
            self::summary($compiled),
        );
    }

    public function testNothingIsInlinedWithoutALookup(): void
    {
        $profile = self::profile(self::OBSERVATION, 'resource', 'Observation', [
            self::element('Observation.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'ident']]),
        ]);

        $compiled = (new StructureDefinitionCompiler())->compile($profile);

        self::assertSame([], $compiled->attributes);
        self::assertSame([], $compiled->diagnostics);
    }

    public function testAChoiceVariantKeepsItsVariantPath(): void
    {
        $compiled = self::compile(
            [self::element('Observation.value[x]:valueQuantity', ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']], 'valueQuantity')],
            [self::BASE . 'qty' => self::quantityProfile(self::BASE . 'qty', [])],
        );

        self::assertSame([[FHIRProfileConstraint::class, 'valueQuantity.comparator', [self::OBSERVATION]]], self::summary($compiled));
    }

    public function testNestedProfilesAndConstraintAncestorsComeAlong(): void
    {
        $quantity = self::quantityProfile(self::BASE . 'qty', []);
        $child    = self::profile(self::BASE . 'range', 'complex-type', 'Range', [
            self::element('Range.low', ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']]),
        ], self::BASE . 'range-parent');
        $parent = self::profile(self::BASE . 'range-parent', 'complex-type', 'Range', [
            ['id' => 'Range.high', 'path' => 'Range.high', 'min' => 1],
        ]);

        $compiled = self::compile(
            [self::element('Observation.referenceRange.age', ['code' => 'Range', 'profile' => [self::BASE . 'range']])],
            [self::BASE . 'qty' => $quantity, self::BASE . 'range' => $child, self::BASE . 'range-parent' => $parent],
        );

        self::assertSame(
            [
                [FHIRProfileConstraint::class, 'referenceRange.age.low.comparator', [self::OBSERVATION]],
                [FHIRProfileConstraint::class, 'referenceRange.age.high', [self::OBSERVATION]],
            ],
            self::summary($compiled),
        );
    }

    public function testACycleIsReportedAndStops(): void
    {
        $a = self::profile(self::BASE . 'a', 'complex-type', 'Identifier', [
            self::element('Identifier.assigner.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'b']]),
        ]);
        $b = self::profile(self::BASE . 'b', 'complex-type', 'Identifier', [
            self::element('Identifier.assigner.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'a']]),
        ]);

        $compiled = self::compile(
            [self::element('Observation.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'a']])],
            [self::BASE . 'a' => $a, self::BASE . 'b' => $b],
        );

        self::assertCount(1, $compiled->diagnostics);
        self::assertStringContainsString(self::BASE . 'a is already being inlined on this path', $compiled->diagnostics[0]->message);
        self::assertStringContainsString('inlined at identifier.assigner.identifier', $compiled->diagnostics[0]->message);
        self::assertSame('Identifier.assigner.identifier', $compiled->diagnostics[0]->elementId);
    }

    public function testNestingPastTheLimitIsReported(): void
    {
        $definitions = [];
        for ($i = 0; $i <= StructureDefinitionCompiler::MAX_INLINE_DEPTH; ++$i) {
            $definitions[self::BASE . "n{$i}"] = self::profile(self::BASE . "n{$i}", 'complex-type', 'Identifier', [
                self::element('Identifier.assigner.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'n' . ($i + 1)]]),
            ]);
        }

        $compiled = self::compile(
            [self::element('Observation.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'n0']])],
            $definitions,
        );

        self::assertCount(1, $compiled->diagnostics);
        self::assertStringContainsString('nest more than 8 deep', $compiled->diagnostics[0]->message);
    }

    /**
     * @param array<string, mixed> $element
     */
    #[DataProvider('notEnforced')]
    public function testUnenforceableReferencesAreReportedNotGuessed(array $element, string $reason): void
    {
        $compiled = self::compile([$element], [
            self::BASE . 'qty'      => self::quantityProfile(self::BASE . 'qty', []),
            self::BASE . 'resource' => self::profile(self::BASE . 'resource', 'resource', 'Composition', []),
        ]);

        $diagnostics = self::inliningDiagnostics($compiled);

        self::assertSame([], $compiled->attributes);
        self::assertCount(1, $diagnostics);
        self::assertStringContainsString($reason, $diagnostics[0]);
        self::assertStringContainsString('not enforced', $diagnostics[0]);
    }

    /**
     * @return iterable<string, array{array<string, mixed>, string}>
     */
    public static function notEnforced(): iterable
    {
        yield 'several types' => [
            ['id' => 'Observation.value[x]', 'path' => 'Observation.value[x]', 'type' => [
                ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']],
                ['code' => 'string'],
            ]],
            'several types or profiles',
        ];
        yield 'several profiles' => [
            self::element('Observation.valueQuantity', ['code' => 'Quantity', 'profile' => [self::BASE . 'qty', self::BASE . 'other']]),
            'several types or profiles',
        ];
        yield 'unknown profile' => [
            self::element('Observation.valueQuantity', ['code' => 'Quantity', 'profile' => [self::BASE . 'missing']]),
            'no StructureDefinition with that URL',
        ];
        yield 'not a data-type profile' => [
            self::element('Observation.section', ['code' => 'BackboneElement', 'profile' => [self::BASE . 'resource']]),
            'not a constraint on a data type',
        ];
        yield 'inside a slice' => [
            ['id' => 'Observation.component:a.valueQuantity', 'path' => 'Observation.component.valueQuantity', 'type' => [
                ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']],
            ]],
            'inside a slice',
        ];
    }

    public function testAMissingAncestorOfADataTypeProfileIsReported(): void
    {
        $compiled = self::compile(
            [self::element('Observation.valueQuantity', ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']])],
            [self::BASE . 'qty' => self::profile(self::BASE . 'qty', 'complex-type', 'Quantity', [
                ['id' => 'Quantity.comparator', 'path' => 'Quantity.comparator', 'max' => '0'],
            ], self::BASE . 'absent-parent')],
        );

        self::assertSame([[FHIRProfileConstraint::class, 'valueQuantity.comparator', [self::OBSERVATION]]], self::summary($compiled));
        self::assertCount(1, self::inliningDiagnostics($compiled));
        self::assertStringContainsString('derives from ' . self::BASE . 'absent-parent, which is not available', self::inliningDiagnostics($compiled)[0]);
    }

    /**
     * A child that restates the data-type profile its parent already gave the element adds nothing:
     * the parent's group enforces it, and a second copy would report each violation twice.
     */
    public function testATypeRestatedFromTheParentIsNotInlinedAgain(): void
    {
        $element            = self::element('Observation.valueQuantity', ['code' => 'Quantity', 'profile' => [self::BASE . 'qty|2.0']]);
        $parent             = self::profile(self::BASE . 'parent', 'resource', 'Observation', [$element]);
        $child              = self::profile(self::OBSERVATION, 'resource', 'Observation', [$element], self::BASE . 'parent');
        $lookup             = static fn (string $url): ?array => [
            self::BASE . 'qty'    => self::quantityProfile(self::BASE . 'qty', []),
            self::BASE . 'parent' => $parent,
        ][$url] ?? null;

        self::assertSame([], (new StructureDefinitionCompiler())->compile($child, $lookup)->attributes);
        self::assertNotSame([], (new StructureDefinitionCompiler())->compile($parent, $lookup)->attributes);
    }

    /**
     * A parent that allowed several types left the element unenforced, so a child that narrows it
     * to one profiled type enforces that profile itself.
     */
    public function testAChildNarrowingAMultiTypeParentElementIsInlined(): void
    {
        $parent = self::profile(self::BASE . 'parent', 'resource', 'Observation', [[
            'id'   => 'Observation.value[x]',
            'path' => 'Observation.value[x]',
            'type' => [['code' => 'Quantity', 'profile' => [self::BASE . 'qty']], ['code' => 'string']],
        ]]);
        $child  = self::profile(self::OBSERVATION, 'resource', 'Observation', [
            self::element('Observation.value[x]:valueQuantity', ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']], 'value[x]'),
        ], self::BASE . 'parent');
        $lookup = static fn (string $url): ?array => [
            self::BASE . 'qty'    => self::quantityProfile(self::BASE . 'qty', []),
            self::BASE . 'parent' => $parent,
        ][$url] ?? null;

        $compiled = (new StructureDefinitionCompiler())->compile($child, $lookup);

        self::assertSame([[FHIRProfileConstraint::class, 'valueQuantity.comparator', [self::OBSERVATION]]], self::summary($compiled));
    }

    /**
     * Slicing a data-type profile inherits is only found in its snapshot, so one without a snapshot
     * is reported instead of enforced from its differential alone.
     */
    public function testAnInlinedProfileWithoutASnapshotIsReported(): void
    {
        $quantity = self::quantityProfile(self::BASE . 'qty', []);
        unset($quantity['snapshot']);

        $compiled = self::compile(
            [self::element('Observation.valueQuantity', ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']])],
            [self::BASE . 'qty' => $quantity],
        );

        self::assertSame([], $compiled->attributes);
        self::assertCount(1, self::inliningDiagnostics($compiled));
        self::assertStringContainsString(self::BASE . 'qty has no snapshot', self::inliningDiagnostics($compiled)[0]);
    }

    /**
     * A data-type profile and its ancestor both define slice "kind"; in one group, two definitions
     * would leave the second with no items, so they merge into the tighter of the two.
     */
    public function testASliceRedefinedByAnAncestorIsMergedIntoOne(): void
    {
        $slicing  = [
            'id'      => 'Identifier.type.coding',
            'path'    => 'Identifier.type.coding',
            'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open'],
        ];
        $system   = ['id' => 'Identifier.type.coding:kind.system', 'path' => 'Identifier.type.coding.system', 'fixedUri' => 'http://example.org/kinds'];
        $ancestor = self::profile(self::BASE . 'ident-base', 'complex-type', 'Identifier', [
            $slicing,
            ['id' => 'Identifier.type.coding:kind', 'path' => 'Identifier.type.coding', 'sliceName' => 'kind', 'min' => 0, 'max' => '2'],
            $system,
        ]);
        $derived  = self::profile(self::BASE . 'ident', 'complex-type', 'Identifier', [
            $slicing,
            ['id' => 'Identifier.type.coding:kind', 'path' => 'Identifier.type.coding', 'sliceName' => 'kind', 'min' => 1, 'max' => '*'],
        ], self::BASE . 'ident-base');
        // The derived snapshot carries the fixed system it inherits, which identifies its slice
        $derived['snapshot']['element'][] = $system;

        $compiled = self::compile(
            [self::element('Observation.identifier', ['code' => 'Identifier', 'profile' => [self::BASE . 'ident']])],
            [self::BASE . 'ident' => $derived, self::BASE . 'ident-base' => $ancestor],
        );

        $slices = array_values(array_filter($compiled->attributes, static fn (CompiledAttribute $a): bool => $a->class === FHIRSliceConstraint::class));
        self::assertCount(1, $slices);
        self::assertSame(1, $slices[0]->arguments['min']);
        self::assertSame(2, $slices[0]->arguments['max']);
        self::assertSame('http://example.org/kinds', $slices[0]->arguments['discriminatorValue'] ?? null);
        self::assertCount(1, array_filter($compiled->attributes, static fn (CompiledAttribute $a): bool => $a->class === FHIRSlicingRules::class));
    }

    public function testExtensionProfilesAreLeftToExtensionValidation(): void
    {
        $compiled = self::compile(
            [self::element('Observation.extension:x', ['code' => 'Extension', 'profile' => [self::BASE . 'ext']])],
            [],
        );

        self::assertSame([], $compiled->attributes);
        self::assertSame([], self::inliningDiagnostics($compiled));
    }

    /**
     * Messages of the diagnostics about data-type profiles, leaving out those about slicing.
     *
     * @return list<string>
     */
    private static function inliningDiagnostics(CompiledProfile $compiled): array
    {
        $messages = [];
        foreach ($compiled->diagnostics as $diagnostic) {
            if (str_contains($diagnostic->message, ' must conform to ')) {
                $messages[] = $diagnostic->message;
            }
        }

        return $messages;
    }

    /**
     * @param list<array<string, mixed>>          $elements    Observation differential elements
     * @param array<string, array<string, mixed>> $definitions Definitions the lookup finds, by URL
     */
    private static function compile(array $elements, array $definitions): CompiledProfile
    {
        return (new StructureDefinitionCompiler())->compile(
            self::profile(self::OBSERVATION, 'resource', 'Observation', $elements),
            static fn (string $url): ?array => $definitions[$url] ?? null,
        );
    }

    /**
     * Class, path and groups of each compiled attribute.
     *
     * @return list<array{class-string, string, mixed}>
     */
    private static function summary(CompiledProfile $compiled): array
    {
        return array_map(
            static fn (CompiledAttribute $attribute): array => [
                $attribute->class,
                (string) ($attribute->arguments['path'] ?? $attribute->arguments['property'] ?? ''),
                $attribute->arguments['groups'] ?? null,
            ],
            $compiled->attributes,
        );
    }

    /**
     * @param array<string, mixed> $type
     *
     * @return array<string, mixed>
     */
    private static function element(string $id, array $type, ?string $path = null): array
    {
        return ['id' => $id, 'path' => $path === null ? $id : 'Observation.' . $path, 'type' => [$type]];
    }

    /**
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function profile(string $url, string $kind, string $type, array $elements, ?string $base = null): array
    {
        $elements = [['id' => $type, 'path' => $type], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'kind'           => $kind,
            'type'           => $type,
            'derivation'     => 'constraint',
            'baseDefinition' => $base ?? 'http://hl7.org/fhir/StructureDefinition/' . $type,
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }

    /**
     * A Quantity profile that forbids a comparator.
     *
     * @param list<array<string, mixed>> $extra
     *
     * @return array<string, mixed>
     */
    private static function quantityProfile(string $url, array $extra): array
    {
        return self::profile($url, 'complex-type', 'Quantity', [
            ['id' => 'Quantity.comparator', 'path' => 'Quantity.comparator', 'max' => '0'],
            ...$extra,
        ]);
    }

    /**
     * An Identifier profile with a fixed system and a required slice of its type's codings.
     *
     * @return array<string, mixed>
     */
    private static function identifierProfile(): array
    {
        return self::profile(self::BASE . 'ident', 'complex-type', 'Identifier', [
            ['id' => 'Identifier.system', 'path' => 'Identifier.system', 'fixedUri' => 'http://example.org/ids'],
            [
                'id'      => 'Identifier.type.coding',
                'path'    => 'Identifier.type.coding',
                'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open'],
            ],
            ['id' => 'Identifier.type.coding:kind', 'path' => 'Identifier.type.coding', 'sliceName' => 'kind', 'min' => 1, 'max' => '1'],
            ['id' => 'Identifier.type.coding:kind.system', 'path' => 'Identifier.type.coding.system', 'fixedUri' => 'http://example.org/kinds'],
        ]);
    }
}

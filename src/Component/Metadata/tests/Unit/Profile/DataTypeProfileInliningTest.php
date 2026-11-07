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
        self::assertStringContainsString('that profile contains itself', $compiled->diagnostics[0]->message);
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
            'not a data-type profile',
        ];
        yield 'inside a slice' => [
            ['id' => 'Observation.component:a.valueQuantity', 'path' => 'Observation.component.valueQuantity', 'type' => [
                ['code' => 'Quantity', 'profile' => [self::BASE . 'qty']],
            ]],
            'inside a slice',
        ];
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
            if (str_contains($diagnostic->message, ' to conform to ')) {
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
        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'kind'           => $kind,
            'type'           => $type,
            'derivation'     => 'constraint',
            'baseDefinition' => $base ?? 'http://hl7.org/fhir/StructureDefinition/' . $type,
            'differential'   => ['element' => [['id' => $type, 'path' => $type], ...$elements]],
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

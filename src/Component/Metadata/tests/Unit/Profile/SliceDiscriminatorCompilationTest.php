<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledAttribute;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Each discriminator of a slicing compiles into the slice's `discriminators`, with the value an item
 * must hold there: from the slice's own fixed or pattern value on `$this`, from a required binding,
 * from the slice's type or profile, or from the data-type profile the slice is typed with.
 */
#[CoversClass(StructureDefinitionCompiler::class)]
final class SliceDiscriminatorCompilationTest extends TestCase
{
    private const string URL = 'http://example.org/fhir/StructureDefinition/sliced';

    /**
     * AU Core smoking status slices `category` on `$this`; the slice's own pattern is its value, so
     * it keeps its minimum and its slicing keeps its closed rules.
     */
    public function testAThisSliceTakesItsOwnPattern(): void
    {
        $pattern  = ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => 'social-history']]];
        $compiled = self::compile('Observation', 'category', 'closed', [['type' => 'pattern', 'path' => '$this']], [
            ['id' => 'Observation.category:socialHistory', 'path' => 'Observation.category', 'sliceName' => 'socialHistory', 'min' => 1, 'max' => '1', 'patternCodeableConcept' => $pattern],
        ]);

        $slice = self::slices($compiled)['socialHistory'];
        self::assertSame([['type' => 'pattern', 'path' => '$this', 'value' => $pattern]], $slice['discriminators']);
        self::assertSame(1, $slice['min']);
        self::assertSame(['closed'], self::slicingRules($compiled));
        self::assertSame([], $compiled->diagnostics);
    }

    /**
     * Every discriminator of a composite slicing is kept, each with its own value, and a slice is
     * unmatchable when any one of them has none.
     */
    public function testEveryDiscriminatorOfACompositeSlicingIsKept(): void
    {
        $compiled = self::compile('Observation', 'code.coding', 'closed', [['type' => 'value', 'path' => 'system'], ['type' => 'value', 'path' => 'code']], [
            ['id' => 'Observation.code.coding:loinc', 'path' => 'Observation.code.coding', 'sliceName' => 'loinc', 'min' => 1],
            ['id' => 'Observation.code.coding:loinc.system', 'path' => 'Observation.code.coding.system', 'fixedUri' => 'http://loinc.org'],
            ['id' => 'Observation.code.coding:loinc.code', 'path' => 'Observation.code.coding.code', 'fixedCode' => '8480-6'],
            ['id' => 'Observation.code.coding:local', 'path' => 'Observation.code.coding', 'sliceName' => 'local', 'min' => 1],
            ['id' => 'Observation.code.coding:local.system', 'path' => 'Observation.code.coding.system', 'fixedUri' => 'http://example.org/codes'],
        ]);

        $slices = self::slices($compiled);
        self::assertSame(
            [['type' => 'value', 'path' => 'system', 'value' => 'http://loinc.org'], ['type' => 'value', 'path' => 'code', 'value' => '8480-6']],
            $slices['loinc']['discriminators'],
        );
        self::assertSame(1, $slices['loinc']['min']);

        // `local` states no code, so no item can be told to belong to it
        self::assertSame([['type' => 'value', 'path' => 'system', 'value' => 'http://example.org/codes'], ['type' => 'value', 'path' => 'code']], $slices['local']['discriminators']);
        self::assertSame(0, $slices['local']['min']);
        self::assertSame(['open'], self::slicingRules($compiled));

        // One for the slice's rules no item reaches, naming the discriminator with no value, one for the relaxed slicing
        self::assertCount(2, $compiled->diagnostics);
        self::assertStringContainsString("Observation.code.coding:local carries rules, but its value discriminator on 'code' cannot be evaluated", $compiled->diagnostics[0]->message);
        self::assertStringContainsString('slice(s) local use a discriminator that cannot be evaluated', $compiled->diagnostics[1]->message);
    }

    /**
     * AU Core's `coding:pbs` has no fixed or pattern value, only a required binding: the value set
     * is the discriminator, and the slice keeps its minimum and its slicing's rules.
     */
    public function testABindingOnlySliceCarriesItsValueSet(): void
    {
        $compiled = self::compile('Medication', 'code.coding', 'closed', [['type' => 'pattern', 'path' => '$this']], [
            ['id' => 'Medication.code.coding:pbs', 'path' => 'Medication.code.coding', 'sliceName' => 'pbs', 'min' => 1, 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/pbs']],
            ['id' => 'Medication.code.coding:loose', 'path' => 'Medication.code.coding', 'sliceName' => 'loose', 'binding' => ['strength' => 'extensible', 'valueSet' => 'http://example.org/ValueSet/loose']],
        ]);

        $slices = self::slices($compiled);
        self::assertSame([['type' => 'pattern', 'path' => '$this', 'valueSet' => 'http://example.org/ValueSet/pbs']], $slices['pbs']['discriminators']);
        self::assertSame(1, $slices['pbs']['min']);

        // An extensible binding allows codes from outside it, so it says nothing about membership
        self::assertSame([['type' => 'pattern', 'path' => '$this']], $slices['loose']['discriminators']);
        self::assertSame(['open'], self::slicingRules($compiled));
    }

    /**
     * A binding the unsliced element carries too tells no item apart. R5's history-bundle `patch`
     * slice has only the http-verb binding every `entry.request.method` has, so it gets no value set.
     */
    public function testABindingEveryItemHasIsNotADiscriminator(): void
    {
        $verbs = ['strength' => 'required', 'valueSet' => 'http://hl7.org/fhir/ValueSet/http-verb'];
        $sd    = self::definition('Bundle', 'entry', 'closed', [['type' => 'value', 'path' => 'request.method']], [
            ['id' => 'Bundle.entry:patch', 'path' => 'Bundle.entry', 'sliceName' => 'patch'],
        ]);

        // The snapshot carries the base binding on the unsliced element and on the slice alike
        $sd['snapshot']['element'][] = ['id' => 'Bundle.entry.request.method', 'path' => 'Bundle.entry.request.method', 'binding' => $verbs];
        $sd['snapshot']['element'][] = ['id' => 'Bundle.entry:patch.request.method', 'path' => 'Bundle.entry.request.method', 'binding' => $verbs];

        $compiled = (new StructureDefinitionCompiler())->compile($sd);

        self::assertSame([['type' => 'value', 'path' => 'request.method']], self::slices($compiled)['patch']['discriminators']);
        self::assertSame(['open'], self::slicingRules($compiled));
    }

    /**
     * A slice that forbids the element a value discriminator reads takes the items without it: the
     * core slicing example's `email` slice forbids `telecom.use` beside two phone slices fixing it.
     */
    public function testASliceForbiddingThePathTakesItemsWithoutIt(): void
    {
        $compiled = self::compile('Patient', 'telecom', 'closed', [['type' => 'value', 'path' => 'system'], ['type' => 'value', 'path' => 'use']], [
            ['id' => 'Patient.telecom:email', 'path' => 'Patient.telecom', 'sliceName' => 'email'],
            ['id' => 'Patient.telecom:email.system', 'path' => 'Patient.telecom.system', 'fixedCode' => 'email'],
            ['id' => 'Patient.telecom:email.use', 'path' => 'Patient.telecom.use', 'max' => '0'],
        ]);

        self::assertSame(
            [['type' => 'value', 'path' => 'system', 'value' => 'email'], ['type' => 'exists', 'path' => 'use', 'value' => false]],
            self::slices($compiled)['email']['discriminators'],
        );
        self::assertSame(['closed'], self::slicingRules($compiled));
    }

    /**
     * A data-type profile that only binds the discriminator path makes that value set the
     * discriminator, as AU Core's `identifier:dva` is keyed by `au-dvanumber`'s bound `type`.
     */
    public function testATypeProfilesBindingAtThePathIsTheValueSet(): void
    {
        $profile = 'http://example.org/fhir/StructureDefinition/dva';
        $sd      = self::definition('Patient', 'identifier', 'open', [['type' => 'pattern', 'path' => 'type']], [
            ['id' => 'Patient.identifier:dva', 'path' => 'Patient.identifier', 'sliceName' => 'dva', 'type' => [['code' => 'Identifier', 'profile' => [$profile]]]],
        ]);
        $dva = [
            'url'        => $profile,
            'kind'       => 'complex-type',
            'type'       => 'Identifier',
            'derivation' => 'constraint',
            'snapshot'   => ['element' => [
                ['id' => 'Identifier', 'path' => 'Identifier'],
                ['id' => 'Identifier.type', 'path' => 'Identifier.type', 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/dva']],
            ]],
            'differential' => ['element' => [
                ['id' => 'Identifier.type', 'path' => 'Identifier.type', 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/dva']],
            ]],
        ];

        $compiled = (new StructureDefinitionCompiler())->compile($sd, static fn (string $url): ?array => $url === $profile ? $dva : null);

        self::assertSame([['type' => 'pattern', 'path' => 'type', 'valueSet' => 'http://example.org/ValueSet/dva']], self::slices($compiled)['dva']['discriminators']);
    }

    /**
     * A `type` discriminator takes the type codes the slice allows at the path, and a `profile`
     * discriminator the profiles, so a document Bundle's closed entry slicing stays closed.
     */
    public function testTypeAndProfileDiscriminatorsTakeTheSlicesTypesAndProfiles(): void
    {
        $byType = self::compile('Bundle', 'entry', 'closed', [['type' => 'type', 'path' => 'resource']], [
            ['id' => 'Bundle.entry:patient', 'path' => 'Bundle.entry', 'sliceName' => 'patient', 'min' => 1],
            ['id' => 'Bundle.entry:patient.resource', 'path' => 'Bundle.entry.resource', 'type' => [['code' => 'Patient']]],
        ]);

        self::assertSame([['type' => 'type', 'path' => 'resource', 'value' => ['Patient']]], self::slices($byType)['patient']['discriminators']);
        self::assertSame(1, self::slices($byType)['patient']['min']);
        self::assertSame(['closed'], self::slicingRules($byType));

        $profile   = 'http://example.org/fhir/StructureDefinition/composition';
        $byProfile = self::compile('Bundle', 'entry', 'closed', [['type' => 'profile', 'path' => 'resource']], [
            ['id' => 'Bundle.entry:composition', 'path' => 'Bundle.entry', 'sliceName' => 'composition', 'min' => 1],
            ['id' => 'Bundle.entry:composition.resource', 'path' => 'Bundle.entry.resource', 'type' => [['code' => 'Composition', 'profile' => [$profile]]]],
        ]);

        self::assertSame([['type' => 'profile', 'path' => 'resource', 'value' => [$profile]]], self::slices($byProfile)['composition']['discriminators']);
        self::assertSame(['closed'], self::slicingRules($byProfile));
    }

    /**
     * A path through `resolve()` is not followed, so its slice stays unmatchable.
     */
    public function testAPathThroughResolveHasNoValue(): void
    {
        $compiled = self::compile('Bundle', 'entry', 'closed', [['type' => 'type', 'path' => 'resource.subject.resolve()']], [
            ['id' => 'Bundle.entry:patient', 'path' => 'Bundle.entry', 'sliceName' => 'patient', 'min' => 1],
        ]);

        self::assertSame([['type' => 'type', 'path' => 'resource.subject.resolve()']], self::slices($compiled)['patient']['discriminators']);
        self::assertSame(['open'], self::slicingRules($compiled));
    }

    /**
     * @param list<array{type: string, path: string}> $discriminators
     * @param list<array<string, mixed>>              $slices
     */
    private static function compile(string $type, string $property, string $rules, array $discriminators, array $slices): CompiledProfile
    {
        return (new StructureDefinitionCompiler())->compile(self::definition($type, $property, $rules, $discriminators, $slices));
    }

    /**
     * A profile on $type slicing $property with the given rules and discriminators, plus $slices.
     *
     * @param list<array{type: string, path: string}> $discriminators
     * @param list<array<string, mixed>>              $slices
     *
     * @return array<string, mixed>
     */
    private static function definition(string $type, string $property, string $rules, array $discriminators, array $slices): array
    {
        $elements = [
            ['id' => $type, 'path' => $type],
            ['id' => "{$type}.{$property}", 'path' => "{$type}.{$property}", 'slicing' => ['discriminator' => $discriminators, 'rules' => $rules]],
            ...$slices,
        ];

        return ['url' => self::URL, 'type' => $type, 'differential' => ['element' => $elements], 'snapshot' => ['element' => $elements]];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function slices(CompiledProfile $compiled): array
    {
        $slices = [];
        foreach ($compiled->attributes as $attribute) {
            if ($attribute->class === FHIRSliceConstraint::class) {
                $slices[(string) $attribute->arguments['sliceName']] = $attribute->arguments;
            }
        }

        return $slices;
    }

    /**
     * @return list<string>
     */
    private static function slicingRules(CompiledProfile $compiled): array
    {
        return array_values(array_map(
            static fn (CompiledAttribute $attribute): string => (string) $attribute->arguments['rules'],
            array_filter($compiled->attributes, static fn (CompiledAttribute $attribute): bool => $attribute->class === FHIRSlicingRules::class),
        ));
    }
}

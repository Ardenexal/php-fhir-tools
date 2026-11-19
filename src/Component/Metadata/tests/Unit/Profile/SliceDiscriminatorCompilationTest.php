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
     * A path through `resolve()` whose reference names no target profile has nothing to match on, so
     * its slice stays unmatchable and its closed slicing is enforced as open.
     */
    public function testAPathThroughResolveWithNoTargetProfileHasNoValue(): void
    {
        $compiled = self::compile('Bundle', 'entry', 'closed', [['type' => 'type', 'path' => 'resource.subject.resolve()']], [
            ['id' => 'Bundle.entry:patient', 'path' => 'Bundle.entry', 'sliceName' => 'patient', 'min' => 1],
        ]);

        self::assertSame([['type' => 'type', 'path' => 'resource.subject.resolve()']], self::slices($compiled)['patient']['discriminators']);
        self::assertSame(['open'], self::slicingRules($compiled));
    }

    /**
     * A `type` discriminator through `resolve()` takes the resource types of the reference's target
     * profiles, as the supplied profile-slicing-type-resolve does for `List.entry.item`. A core
     * resource profile names its type; any other is looked up for its `type`.
     */
    public function testATypeDiscriminatorThroughResolveTakesTheTargetTypes(): void
    {
        $definition = self::definition('List', 'entry', 'closed', [['type' => 'type', 'path' => 'item.resolve()']], [
            ['id' => 'List.entry:conditions', 'path' => 'List.entry', 'sliceName' => 'conditions', 'min' => 1, 'max' => '1'],
            ['id' => 'List.entry:conditions.item', 'path' => 'List.entry.item', 'type' => [['code' => 'Reference', 'targetProfile' => ['http://hl7.org/fhir/StructureDefinition/Condition']]]],
            ['id' => 'List.entry:vitals', 'path' => 'List.entry', 'sliceName' => 'vitals', 'min' => 1],
            ['id' => 'List.entry:vitals.item', 'path' => 'List.entry.item', 'type' => [['code' => 'Reference', 'targetProfile' => ['http://hl7.org/fhir/StructureDefinition/vitalsigns|4.0.1']]]],
        ]);

        $lookup   = static fn (string $url): ?array => $url === 'http://hl7.org/fhir/StructureDefinition/vitalsigns' ? ['url' => $url, 'kind' => 'resource', 'type' => 'Observation'] : null;
        $compiled = (new StructureDefinitionCompiler())->compile($definition, $lookup);
        $slices   = self::slices($compiled);

        self::assertSame([['type' => 'type', 'path' => 'item.resolve()', 'value' => ['Condition']]], $slices['conditions']['discriminators']);
        self::assertSame([['type' => 'type', 'path' => 'item.resolve()', 'value' => ['Observation']]], $slices['vitals']['discriminators']);
        self::assertSame(1, $slices['conditions']['min']);
        self::assertSame(['closed'], self::slicingRules($compiled));

        // `Reference(Any)` targets the abstract Resource, which no resource is, so it gives no value
        $any = self::compile('List', 'entry', 'closed', [['type' => 'type', 'path' => 'item.resolve()']], [
            ['id' => 'List.entry:any', 'path' => 'List.entry', 'sliceName' => 'any', 'min' => 1],
            ['id' => 'List.entry:any.item', 'path' => 'List.entry.item', 'type' => [['code' => 'Reference', 'targetProfile' => ['http://hl7.org/fhir/StructureDefinition/Resource']]]],
        ]);
        self::assertSame([['type' => 'type', 'path' => 'item.resolve()']], self::slices($any)['any']['discriminators']);
        self::assertSame(['open'], self::slicingRules($any));

        // Without a lookup the IG profile's type is unknown, so that slice has nothing to match on
        $unlooked = self::slices((new StructureDefinitionCompiler())->compile($definition));
        self::assertSame([['type' => 'type', 'path' => 'item.resolve()']], $unlooked['vitals']['discriminators']);
    }

    /**
     * A `profile` discriminator through `resolve()` takes the target profiles themselves, on the slice
     * (`resolve()`, `$this.resolve()`) or beneath it, and through a choice element narrowed with
     * `ofType(Reference)`.
     */
    public function testAProfileDiscriminatorThroughResolveTakesTheTargetProfiles(): void
    {
        $profile  = 'http://example.org/fhir/StructureDefinition/prosthesis';
        $compiled = self::compile('Appointment', 'supportingInformation', 'closed', [['type' => 'profile', 'path' => '$this.resolve()']], [
            ['id' => 'Appointment.supportingInformation:prosthesis', 'path' => 'Appointment.supportingInformation', 'sliceName' => 'prosthesis', 'max' => '1', 'type' => [['code' => 'Reference', 'targetProfile' => [$profile]]]],
        ]);

        self::assertSame([['type' => 'profile', 'path' => '$this.resolve()', 'value' => [$profile]]], self::slices($compiled)['prosthesis']['discriminators']);
        self::assertSame(['closed'], self::slicingRules($compiled));

        $choice = self::compile('Communication', 'payload', 'open', [['type' => 'type', 'path' => 'content.ofType(Reference).resolve()']], [
            ['id' => 'Communication.payload:task', 'path' => 'Communication.payload', 'sliceName' => 'task'],
            ['id' => 'Communication.payload:task.content[x]', 'path' => 'Communication.payload.content[x]', 'type' => [['code' => 'Reference', 'targetProfile' => ['http://hl7.org/fhir/StructureDefinition/Task']]]],
        ]);

        self::assertSame([['type' => 'type', 'path' => 'content.ofType(Reference).resolve()', 'value' => ['Task']]], self::slices($choice)['task']['discriminators']);
    }

    /**
     * A `value` or `pattern` discriminator through `resolve()` takes the fixed or pattern value the
     * reference's one target profile states at the rest of the path: the core lipid profile's
     * `resolve().code` slices are told apart by the code each Observation profile fixes.
     */
    public function testAValueDiscriminatorThroughResolveReadsTheTargetProfile(): void
    {
        $code        = ['coding' => [['system' => 'http://loinc.org', 'code' => '35200-5']]];
        $cholesterol = 'http://hl7.org/fhir/StructureDefinition/cholesterol';
        $definition  = self::definition('DiagnosticReport', 'result', 'closed', [['type' => 'value', 'path' => 'resolve().code']], [
            ['id' => 'DiagnosticReport.result:Cholesterol', 'path' => 'DiagnosticReport.result', 'sliceName' => 'Cholesterol', 'min' => 1, 'max' => '1', 'type' => [['code' => 'Reference', 'targetProfile' => [$cholesterol]]]],
        ]);

        $lookup = static fn (string $url): ?array => $url === $cholesterol ? [
            'url'      => $url,
            'kind'     => 'resource',
            'type'     => 'Observation',
            'snapshot' => ['element' => [
                ['id' => 'Observation', 'path' => 'Observation'],
                ['id' => 'Observation.code', 'path' => 'Observation.code', 'fixedCodeableConcept' => $code],
            ]],
        ] : null;

        $compiled = (new StructureDefinitionCompiler())->compile($definition, $lookup);
        $slice    = self::slices($compiled)['Cholesterol'];

        self::assertSame([['type' => 'value', 'path' => 'resolve().code', 'value' => $code]], $slice['discriminators']);
        self::assertSame(1, $slice['min']);
        self::assertSame(['closed'], self::slicingRules($compiled));
        self::assertSame([], $compiled->diagnostics);

        // Without the target profile there is nothing to match on, as before
        self::assertSame(['open'], self::slicingRules((new StructureDefinitionCompiler())->compile($definition)));
    }

    /**
     * A target profile that binds the element instead of fixing it gives its value set, as the lipid
     * profile's `ldlcholesterol` binds `code` to the LDL codes. A binding it only inherits does not.
     */
    public function testAValueDiscriminatorThroughResolveTakesTheTargetProfilesBinding(): void
    {
        $ldl        = 'http://hl7.org/fhir/StructureDefinition/ldlcholesterol';
        $valueSet   = 'http://hl7.org/fhir/ValueSet/ldlcholesterol-codes|4.0.1';
        $code       = ['id' => 'Observation.code', 'path' => 'Observation.code', 'binding' => ['strength' => 'required', 'valueSet' => $valueSet]];
        $definition = self::definition('DiagnosticReport', 'result', 'closed', [['type' => 'value', 'path' => 'resolve().code']], [
            ['id' => 'DiagnosticReport.result:LDL', 'path' => 'DiagnosticReport.result', 'sliceName' => 'LDL', 'max' => '1', 'type' => [['code' => 'Reference', 'targetProfile' => [$ldl]]]],
        ]);

        $stated = static fn (string $url): ?array => ['url' => $url, 'kind' => 'resource', 'type' => 'Observation', 'derivation' => 'constraint', 'snapshot' => ['element' => [$code]], 'differential' => ['element' => [$code]]];
        self::assertSame(
            [['type' => 'value', 'path' => 'resolve().code', 'valueSet' => $valueSet]],
            self::slices((new StructureDefinitionCompiler())->compile($definition, $stated))['LDL']['discriminators'],
        );

        $inherited = static fn (string $url): ?array => ['url' => $url, 'kind' => 'resource', 'type' => 'Observation', 'derivation' => 'constraint', 'snapshot' => ['element' => [$code]]];
        self::assertSame(
            [['type' => 'value', 'path' => 'resolve().code']],
            self::slices((new StructureDefinitionCompiler())->compile($definition, $inherited))['LDL']['discriminators'],
        );
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

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Locks the nested-element constraint cascade and the two defects it exposed.
 *
 * Constraints declared on backbone elements and datatypes only run because the model generator emits
 * `#[Assert\Valid]` on properties holding nested FHIR objects. Symfony's validator descends nowhere
 * else. Before that, 117 / 123 / 179 invariant declarations in R4 / R4B / R5 were unreachable, and a
 * resource with an invalid nested element was reported valid — the worst possible failure for a
 * validator, because callers trust it.
 *
 * These tests fail if the emission is removed from FHIRModelGenerator, or if the two semantics fixes
 * that had to land alongside it regress.
 */
#[CoversClass(FHIRValidationService::class)]
final class NestedInvariantCascadeTest extends TestCase
{
    private const VALIDATOR_DIR = __DIR__ . '/../../../../../vendor/fhir/fhir-test-cases/validator';

    /**
     * The discriminating pair, and the reason it is a *pair*.
     *
     * `containedToContainer` and `hakan-se` must end in OPPOSITE states. Any change that makes both
     * pass, or both fail, is wrong regardless of what the suite total does — silencing `ref-1`
     * everywhere would "fix" the first while destroying the check the second depends on.
     *
     * The HL7 Java reference validator reports zero issues on `containedToContainer` and does report
     * `ref-1` on `hakan-se`, so this pair pins us to the oracle rather than to our own output.
     */
    public function testContainedToContainerIsCleanWhileHakanSeStillReportsRef1(): void
    {
        $clean = $this->validateFixture('containedToContainer.xml', FhirVersion::R4);

        self::assertSame(
            [],
            array_map(static fn (FHIRValidationViolation $v): string => $v->message, $clean),
            'containedToContainer must report zero errors — the Java reference validator passes it '
            . 'with no issues. Its References evaluate ref-1 to an EMPTY collection (a bare "#" gives '
            . '"false or {}", and a display-only Reference gives {} throughout); empty is "unknown" '
            . 'in FHIRPath, not "false", so it must not be reported as non-conformance.',
        );

        $dirty = $this->validateFixture('hakan-se.json', FhirVersion::R4);
        $keys  = array_map(static fn (FHIRValidationViolation $v): ?string => $v->invariantKey, $dirty);

        self::assertContains(
            'ref-1',
            $keys,
            'hakan-se must still report ref-1 — Java does. Its local reference resolves to a hard '
            . 'boolean false, not empty, which is exactly what distinguishes it from the case above.',
        );
    }

    /**
     * A constraint on a nested backbone element must fire when the enclosing resource is validated.
     *
     * This is the defect in one assertion. `Parameters.parameter` carries `inv-1`
     * ("A parameter must have one and only one of (value, resource, part)"); a violating
     * ParametersParameter reported zero errors nested and one error as the validation root, because
     * nothing descended into it.
     */
    public function testConstraintOnNestedBackboneElementIsEvaluated(): void
    {
        $json = json_encode([
            'resourceType' => 'Parameters',
            'parameter'    => [[
                // Both valueString and resource present, which inv-1 forbids.
                'name'        => 'broken',
                'valueString' => 'x',
                'resource'    => ['resourceType' => 'Patient', 'id' => 'p1'],
            ]],
        ], JSON_THROW_ON_ERROR);

        $resource = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);
        $report   = OracleValidationServiceFactory::create(FhirVersion::R4)->validate($resource);

        self::assertContains(
            'inv-1',
            array_map(static fn (FHIRValidationViolation $v): ?string => $v->invariantKey, $report->errors()),
            'inv-1 is declared on the ParametersParameter backbone element. If this assertion fails, '
            . '#[Assert\Valid] is no longer being emitted for nested properties and every backbone '
            . 'and datatype constraint is silently dead again.',
        );
    }

    /**
     * Reading a declared-but-uninitialized typed property is an Error, not a null.
     *
     * Generated models back choice elements with typed properties carrying no default, so
     * `ObservationResource::$value` stays uninitialized whenever the instance has no value. Probing
     * it with `property_exists()` and then reading used to throw
     * "must not be accessed before initialization" and abort validation entirely — which is worse
     * than a wrong answer, because a crashed case silently leaves any conformance comparison.
     */
    public function testObservationWithoutValueValidatesInsteadOfCrashing(): void
    {
        $json = json_encode([
            'resourceType' => 'Observation',
            'status'       => 'final',
            'code'         => ['coding' => [['system' => 'http://loinc.org', 'code' => '3141-9']]],
            'component'    => [[
                'code'          => ['coding' => [['system' => 'http://loinc.org', 'code' => '8480-6']]],
                'valueQuantity' => ['value' => 120, 'unit' => 'mm[Hg]', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]'],
            ]],
        ], JSON_THROW_ON_ERROR);

        $resource = FHIRSerializationService::createDefault(FhirVersion::R5)->deserialize($json);

        // The assertion is that this does not throw.
        $report = OracleValidationServiceFactory::create(FhirVersion::R5)->validate($resource);

        self::assertSame([], $report->errors(), 'a valid component-only Observation must report no errors');
    }

    /**
     * A value set with no enumerable codes must degrade, not fatal.
     *
     * `AllLanguages` spans the whole of BCP-47, so the generator emits `enum AllLanguages {}` — no
     * cases, no backing type. `tryFrom()` on a pure enum is a fatal "Call to undefined method".
     */
    public function testUnenumerableValueSetBindingDoesNotFatal(): void
    {
        $json = json_encode([
            'resourceType'  => 'Patient',
            'id'            => 'lang',
            'communication' => [[
                'language' => ['coding' => [['system' => 'urn:ietf:bcp:47', 'code' => 'en-AU']]],
            ]],
        ], JSON_THROW_ON_ERROR);

        $resource = FHIRSerializationService::createDefault(FhirVersion::R5)->deserialize($json);
        $report   = OracleValidationServiceFactory::create(FhirVersion::R5)->validate($resource);

        self::assertSame(
            [],
            $report->errors(),
            'an unenumerable required binding must surface as a coverage warning, never an error or a fatal',
        );
    }

    /** @return list<FHIRValidationViolation> */
    private function validateFixture(string $file, FhirVersion $version): array
    {
        $path = self::VALIDATOR_DIR . '/' . $file;

        if (!file_exists($path)) {
            self::markTestSkipped("fhir/fhir-test-cases not installed — missing {$path}");
        }

        $resource = FHIRSerializationService::createDefault($version)
            ->deserialize((string) file_get_contents($path));

        return OracleValidationServiceFactory::create($version)->validate($resource)->errors();
    }
}

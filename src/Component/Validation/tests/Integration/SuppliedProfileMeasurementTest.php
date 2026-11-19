<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\Classification;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonHarness;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonReport;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\SkipReason;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\SuppliedProfiles;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Guards the oracle's supplied-profile run: the fhir-test-cases that hand the reference validator
 * their own StructureDefinitions, and every `profile` phase.
 *
 * These cases exercise IG profiles, and slicing above all, which the default run cannot load. On the
 * day they were wired in, some already reported errors the reference validator does not. Those are
 * pinned below as the starting point: a case may leave the list as the validator improves, but no
 * case may join it. Missing-finding totals are not pinned, for the reason MissingFindingMeasurementTest
 * gives; read them with `compare-java-outcomes.php <release> --supplied-profiles`.
 */
final class SuppliedProfileMeasurementTest extends TestCase
{
    /**
     * Cases that reported more errors than the reference validator when this run was introduced.
     *
     * Remove a case once it stops being ABOVE; the test fails until you do, so an improvement is
     * recorded rather than silently absorbed.
     *
     * `type-ref-unchecked [profile]` is ABOVE by design: this project reads a reference's type from
     * its `Type/id` URL by default (#229), which the reference validator does only under
     * `assumeValidRestReferences`. Its twin `type-ref-checked` sets that option and agrees with us.
     * See DeclaredLimitations::REASON_ASSUMED_REST_REFERENCE.
     */
    private const array KNOWN_ABOVE = [
        'R4' => [
            'bundle-invariant',
            'contained-invariant',
            'ext-ctxt-bad-active',
            'extb-ctxt-good',
            'patient-min-pattern [profile]',
            'patient-min-pattern1 [profile]',
            'type-ref-unchecked [profile]',
            'type-subtype-slicing1 [profile]',
        ],
        'R5' => [
            'patient-patternidentifier-good [profile]',
        ],
    ];

    /**
     * Profile phases whose source has no seeded fixture: a package URL, or a profile the reference
     * validator could not snapshot. A re-seed that loses a snapshot lands here, not in ABOVE.
     */
    private const array KNOWN_UNSEEDED_PHASES = [
        'R4' => [
            'PulseOxBundle_25Oct2022 [profile]',
            'de-address-example [profile]',
        ],
        'R5' => [
            'invariant.example [profile]',
            'patient-au-5 [profile]',
            'slice-profile-and-local-patient [profile]',
        ],
    ];

    /** @var array<string, ComparisonReport> */
    private static array $reports = [];

    /** @return iterable<string, array{FhirVersion}> */
    public static function versions(): iterable
    {
        yield 'R4' => [FhirVersion::R4];
        yield 'R5' => [FhirVersion::R5];
    }

    /**
     * No case reports an error the reference validator does not, beyond those pinned at the start.
     */
    #[DataProvider('versions')]
    public function testNoNewCaseReportsMoreErrorsThanTheReferenceValidator(FhirVersion $version): void
    {
        $above = array_map(
            static fn ($comparison): string => $comparison->name,
            $this->report($version)->byClassification(Classification::Above),
        );
        sort($above);

        self::assertSame(
            self::KNOWN_ABOVE[$version->value],
            $above,
            'a case joined or left ABOVE; a new one is a false positive to fix, a departed one should leave KNOWN_ABOVE',
        );
    }

    /** A phase that loses its fixture leaves the comparison, which could also lower ABOVE. */
    #[DataProvider('versions')]
    public function testOnlyKnownProfilePhasesLackASeededDefinition(FhirVersion $version): void
    {
        $unseeded = array_keys(array_filter(
            $this->report($version)->skips,
            static fn (SkipReason $reason): bool => $reason === SkipReason::NoSuppliedDefinition,
        ));
        sort($unseeded);

        self::assertSame(self::KNOWN_UNSEEDED_PHASES[$version->value], $unseeded);
    }

    /** A crash takes a case out of the comparison, which would lower ABOVE without fixing anything. */
    #[DataProvider('versions')]
    public function testNoCaseCrashesTheValidator(FhirVersion $version): void
    {
        self::assertSame([], $this->report($version)->crashedCases());
    }

    /**
     * The profile phases really run: without them the supplied-profile run would only re-measure base
     * validation and could not see slicing at all.
     */
    #[DataProvider('versions')]
    public function testProfilePhasesAreCompared(FhirVersion $version): void
    {
        $phases = array_filter(
            $this->report($version)->comparisons,
            static fn ($comparison): bool => str_ends_with($comparison->name, ' [profile]'),
        );

        self::assertGreaterThan(20, count($phases));
    }

    private function report(FhirVersion $version): ComparisonReport
    {
        $supplied = SuppliedProfiles::forVersion($version);
        if ($supplied === null) {
            self::fail("no supplied-profile fixtures for {$version->value}; run Fixtures/SuppliedProfiles/seed-supplied-profiles.php");
        }

        if (isset(self::$reports[$version->value])) {
            return self::$reports[$version->value];
        }

        $harness = new ComparisonHarness(
            vendorDir: \dirname(__DIR__, 5) . '/vendor',
            validation: OracleValidationServiceFactory::create($version),
            serialization: FHIRSerializationService::createWithIG(version: $version, includeBaseProfiles: true),
            version: $version,
            suppliedProfiles: $supplied,
        );

        // SliceDiscriminatorMatcher warns once per item it meets in a slice discriminated by type or
        // profile, which several supplied profiles use. The warning is known and is not a finding, so
        // only that one is set aside here; any other warning still reaches PHPUnit.
        set_error_handler(
            static fn (int $level, string $message): bool => str_contains($message, 'FHIRSliceDiscriminatorMatcher: discriminator type'),
            E_USER_WARNING,
        );
        try {
            return self::$reports[$version->value] = $harness->run();
        } finally {
            restore_error_handler();
        }
    }
}

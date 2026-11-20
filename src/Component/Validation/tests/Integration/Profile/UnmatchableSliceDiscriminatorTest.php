<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Models\R4\Profile\ExampleLipidProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DiagnosticReportResource;
use Ardenexal\FHIRTools\Component\Models\R5\Profile\SubscriptionNotificationBundleProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CoreStructureDefinitionPackages;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Core profiles whose slices the validator cannot match must not reject resources that conform.
 *
 * Subscription notification bundles slice `entry` on an `exists` discriminator whose path no slice
 * constrains, and the lipid profile slices `result` on `resolve().code`, which cannot be evaluated for
 * a reference that does not resolve. Neither may cap a slice's count or reject items under closed or
 * openAtEnd rules. A lipid result that does resolve is matched, so the slicing is still enforced. Each instance is checked through its generated class and at runtime from the
 * StructureDefinition, which must agree.
 */
#[CoversNothing]
final class UnmatchableSliceDiscriminatorTest extends TestCase
{
    private const string NOTIFICATION_BUNDLE = 'http://hl7.org/fhir/StructureDefinition/subscription-notification-bundle';

    private const string LIPID_PROFILE = 'http://hl7.org/fhir/StructureDefinition/lipidprofile';

    /**
     * @return iterable<string, array{bool}>
     */
    public static function sides(): iterable
    {
        yield 'generated class' => [false];
        yield 'runtime definition' => [true];
    }

    /**
     * A status entry followed by a notification entry: the `first` slice must not claim both.
     */
    #[DataProvider('sides')]
    public function testNotificationBundleWithTwoEntriesHasNoSliceFindings(bool $runtime): void
    {
        $json = json_encode([
            'resourceType' => 'Bundle',
            'type'         => 'subscription-notification',
            'timestamp'    => '2026-11-11T00:00:00Z',
            'entry'        => [
                ['fullUrl' => 'urn:uuid:3f1f0e4e-0d7b-4a3e-9b1a-1c6f2b9e0a01', 'resource' => [
                    'resourceType' => 'SubscriptionStatus', 'status' => 'active', 'type' => 'event-notification',
                    'subscription' => ['reference' => 'Subscription/example'],
                ]],
                ['fullUrl' => 'http://example.org/fhir/Encounter/1', 'resource' => [
                    'resourceType' => 'Encounter', 'id' => '1', 'status' => 'completed',
                ]],
            ],
        ], JSON_THROW_ON_ERROR);

        $instance = self::instance(FhirVersion::R5, $json, $runtime ? BundleResource::class : SubscriptionNotificationBundleProfile::class);

        self::assertSame([], self::sliceFindings(self::service(FhirVersion::R5, $runtime), $instance, self::NOTIFICATION_BUNDLE, 'entry'));
    }

    /**
     * A lipid report with one result reference that does not resolve: no item may be rejected for
     * matching no slice, and no slice's minimum is checked.
     */
    #[DataProvider('sides')]
    public function testLipidReportResultIsNotRejectedByClosedSlicing(bool $runtime): void
    {
        $json = json_encode([
            'resourceType' => 'DiagnosticReport',
            'status'       => 'final',
            'code'         => ['coding' => [['system' => 'http://loinc.org', 'code' => '57698-3']]],
            'result'       => [['reference' => 'Observation/cholesterol']],
        ], JSON_THROW_ON_ERROR);

        $instance = self::instance(FhirVersion::R4, $json, $runtime ? DiagnosticReportResource::class : ExampleLipidProfile::class);

        self::assertSame([], self::sliceFindings(self::service(FhirVersion::R4, $runtime), $instance, self::LIPID_PROFILE, 'result'));
    }

    /**
     * Lipid results in `contained` resolve, so each is matched to the slice its code names: a report
     * with cholesterol and triglyceride results but no HDL cholesterol is missing a required slice.
     */
    #[DataProvider('sides')]
    public function testLipidReportResultsAreMatchedThroughResolve(bool $runtime): void
    {
        $observation = static fn (string $id, string $code, string $display): array => [
            'resourceType' => 'Observation',
            'id'           => $id,
            'status'       => 'final',
            'code'         => ['coding' => [['system' => 'http://loinc.org', 'code' => $code, 'display' => $display]]],
        ];
        $report = static fn (array $contained): string => json_encode([
            'resourceType' => 'DiagnosticReport',
            'contained'    => $contained,
            'status'       => 'final',
            'code'         => ['coding' => [['system' => 'http://loinc.org', 'code' => '57698-3']]],
            'result'       => array_map(static fn (array $o): array => ['reference' => '#' . $o['id']], $contained),
        ], JSON_THROW_ON_ERROR);

        // The profiles fix these displays, zero-width space included
        $cholesterol  = $observation('chol', '35200-5', "Cholesterol [Moles/\u{200B}volume] in Serum or Plasma");
        $triglyceride = $observation('trig', '35217-9', "Triglyceride [Moles/\u{200B}volume] in Serum or Plasma");
        $hdl          = $observation('hdl', '2085-9', 'HDL Cholesterol');
        $glucose      = $observation('glu', '2345-7', 'Glucose');
        $service      = self::service(FhirVersion::R4, $runtime);
        $class        = $runtime ? DiagnosticReportResource::class : ExampleLipidProfile::class;

        self::assertSame([], self::sliceFindings($service, self::instance(FhirVersion::R4, $report([$cholesterol, $triglyceride, $hdl]), $class), self::LIPID_PROFILE, 'result'));

        self::assertSame(
            ['Slice "HDLCholesterol" on "result" requires at least 1 item(s), but 0 matched.'],
            self::sliceFindings($service, self::instance(FhirVersion::R4, $report([$cholesterol, $triglyceride]), $class), self::LIPID_PROFILE, 'result'),
        );

        // The LDL slice is keyed by a required binding, which only a terminology client answers, so
        // without one a glucose result might be LDL cholesterol and closed slicing cannot reject it
        self::assertSame([], self::sliceFindings($service, self::instance(FhirVersion::R4, $report([$cholesterol, $triglyceride, $hdl, $glucose]), $class), self::LIPID_PROFILE, 'result'));
    }

    /**
     * The resource in $json as an instance of $class: the base resource, or a generated profile class
     * extending it, which the serializer does not produce from JSON, so its state is copied over.
     *
     * @param class-string $class
     */
    private static function instance(FhirVersion $version, string $json, string $class): object
    {
        $resource = FHIRSerializationService::createDefault($version)->deserialize($json);
        if ($resource instanceof $class) {
            return $resource;
        }

        self::assertTrue(is_subclass_of($class, $resource::class), "{$class} extends " . $resource::class);
        $instance = new $class();
        foreach (get_object_vars($resource) as $property => $value) {
            $instance->{$property} = $value;
        }

        return $instance;
    }

    /**
     * The profile's findings on the sliced property, other than its own cardinality.
     *
     * @return list<string>
     */
    private static function sliceFindings(FHIRValidationService $service, object $instance, string $url, string $property): array
    {
        $findings = [];
        foreach ($service->validate($instance, [$url])->violations as $violation) {
            if ($violation->profileGroup === $url && $violation->path === $property && self::isSliceFinding($violation)) {
                $findings[] = $violation->message;
            }
        }

        return $findings;
    }

    private static function isSliceFinding(FHIRValidationViolation $violation): bool
    {
        // "Slice …" and "… slicing …" findings; the property's own Count findings say neither
        return stripos($violation->message, 'slic') !== false;
    }

    private static function service(FhirVersion $version, bool $runtime): FHIRValidationService
    {
        if (!$runtime) {
            return OracleValidationServiceFactory::create($version);
        }

        $directory = CoreStructureDefinitionPackages::parityFixtures($version);
        if ($directory === null) {
            self::markTestSkipped("No {$version->value} parity fixtures");
        }

        return OracleValidationServiceFactory::create(
            $version,
            true,
            new RuntimeProfileResolver(new PackageDirectoryStructureDefinitionRegistry($directory, $version->value)),
        );
    }
}

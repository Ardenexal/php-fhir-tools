<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Regression test on a real implementation guide: AU Core 2.0.0, validated at runtime from its
 * StructureDefinitions.
 *
 * Every example AU Core publishes must validate without errors against the profiles in its
 * meta.profile (all but `Specimen-nasoswab` name one), and a few broken copies of them must report the errors AU Core defines for them:
 * the IHI checks, the Medicare number's fixed system, the smoking status category and the blood
 * pressure components. These cover pattern, `$this` and composite slice discriminators, invariants
 * inside a data-type profile on a slice, and slice minimums.
 *
 * The examples and definitions are committed fixtures under `tests/Fixtures/Profile/au-core/`,
 * written by `seed-au-core.php` from the published packages (CC0-1.0). The seed script checks that
 * the trimmed definitions give every example the same findings as the full packages.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(RuntimeProfileResolver::class)]
final class AuCoreProfileTest extends TestCase
{
    private const string FIXTURES = __DIR__ . '/../../Fixtures/Profile/au-core';

    private static ?FHIRValidationService $service = null;

    /**
     * @return iterable<string, array{string}>
     */
    public static function examples(): iterable
    {
        foreach (glob(self::FIXTURES . '/examples/*.json') ?: [] as $file) {
            yield basename($file, '.json') => [$file];
        }
    }

    public function testFixtureHoldsAll65AuCoreExamples(): void
    {
        self::assertCount(65, iterator_to_array(self::examples()), 'AU Core 2.0.0 publishes 65 examples; re-seed with seed-au-core.php');
    }

    #[DataProvider('examples')]
    public function testPublishedExampleValidatesWithoutErrors(string $file): void
    {
        $report = $this->validate($this->load(basename($file)));

        self::assertSame([], $this->errorLines($report));
        self::assertSame([], $report->uncheckedProfiles(), 'every profile in meta.profile must be resolved');
    }

    public function testIhiThatFailsTheLuhnCheckIsAnError(): void
    {
        $patient                           = $this->load('Patient-banks-mia-leanne.json');
        $patient['identifier'][0]['value'] = '8003608333647262';

        self::assertSame(
            ['identifier[0] | IHI shall pass the Luhn algorithm check'],
            $this->errorLines($this->validate($patient)),
        );
    }

    public function testIhiThatIsNotSixteenDigitsIsAnError(): void
    {
        $patient                           = $this->load('Patient-banks-mia-leanne.json');
        $patient['identifier'][0]['value'] = '800360833364';

        self::assertSame(
            ['identifier[0] | IHI shall be an exactly 16 digit number'],
            $this->errorLines($this->validate($patient)),
        );
    }

    public function testMedicareNumberWithAnotherSystemIsAnError(): void
    {
        $patient                 = $this->load('Patient-bennelong-anne.json');
        $patient['identifier'][] = [
            'type'   => ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'MC']]],
            'system' => 'http://example.org/medicare',
            'value'  => '2950156481',
        ];

        self::assertSame(
            ["identifier[1].system | Value is 'http://example.org/medicare' but is fixed to 'http://ns.electronichealth.net.au/id/medicare-number'"],
            $this->errorLines($this->validate($patient)),
        );
    }

    public function testSmokingStatusWithoutTheSocialHistoryCategoryIsAnError(): void
    {
        $observation                                     = $this->load('Observation-smokingstatus-current-smoker.json');
        $observation['category'][0]['coding'][0]['code'] = 'exam';

        self::assertSame(
            ['category | Slice "socialHistory" on "category" requires at least 1 item(s), but 0 matched.'],
            $this->errorLines($this->validate($observation)),
        );
    }

    public function testBloodPressureWithoutASystolicComponentIsAnError(): void
    {
        $observation = $this->load('Observation-bloodpressure-1.json');
        array_shift($observation['component']);

        self::assertSame(
            [
                'component | This collection should contain 2 element or more.|This collection should contain 2 elements or more.',
                'component | Slice "SystolicBP" on "component" requires at least 1 item(s), but 0 matched.',
            ],
            $this->errorLines($this->validate($observation)),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function load(string $example): array
    {
        $decoded = json_decode((string) file_get_contents(self::FIXTURES . '/examples/' . $example), true, flags: JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }

    /**
     * @param array<string, mixed> $resource
     */
    private function validate(array $resource): FHIRValidationReport
    {
        self::$service ??= FHIRValidationServiceBuilder::create()
            ->withPackageDirectories(self::FIXTURES . '/definitions')
            ->build();

        return self::$service->validateWithOptions(
            FHIRSerializationService::createDefault()->deserialize(json_encode($resource, JSON_THROW_ON_ERROR)),
            new FHIRValidationOptions(profilesFromMeta: true),
        );
    }

    /**
     * @return list<string>
     */
    private function errorLines(FHIRValidationReport $report): array
    {
        return array_map(static fn (FHIRValidationViolation $v): string => $v->path . ' | ' . $v->message, $report->errors());
    }
}

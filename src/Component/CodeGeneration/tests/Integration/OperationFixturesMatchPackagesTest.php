<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Integration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Fixtures\OperationFixtureExtractor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The committed operation fixtures still match the FHIR packages they were extracted from.
 *
 * Four tests treat `Fixtures/OperationManifests/` and `Fixtures/TypeIndex/` as specification data —
 * the output-shape classifier, the class-namer collision check, the per-operation fidelity check and
 * the variant coverage gate all assert against them, and all four claim to be "written against the
 * package contents, so they stay true when packages update". That claim was only half true: the
 * fixtures were committed, but **nothing in the repository produced them**, so nobody could refresh
 * them after a package bump without re-deriving the extraction by hand.
 *
 * This test closes the loop. If the packages move and the fixtures do not, it fails here — pointing at
 * `seed-operation-fixtures.php` — rather than surfacing later as a confusing failure inside a
 * classifier or a fidelity check, where the natural reading is "the generator broke".
 *
 * ## Why it skips instead of failing when the cache is absent
 *
 * `demo/var/cache/dev/.fhir/` is gitignored and its contents depend on which packages the developer
 * last pulled, so a test that *required* it would fail for reasons unrelated to the code (M01 note N4)
 * and would be red on any clean checkout. Skipping keeps the fixtures authoritative for everyone while
 * still catching drift for anyone who has generated models — which is anyone who could cause the drift.
 *
 * @see OperationFixtureExtractor for what is carried and why
 */
final class OperationFixturesMatchPackagesTest extends TestCase
{
    private const string MANIFEST_DIR = __DIR__ . '/../Fixtures/OperationManifests';

    private const string INDEX_DIR    = __DIR__ . '/../Fixtures/TypeIndex';

    /**
     * @return \Generator<string, array{string}>
     */
    public static function versions(): \Generator
    {
        foreach (['r4', 'r4b', 'r5'] as $version) {
            yield strtoupper($version) => [$version];
        }
    }

    #[DataProvider('versions')]
    public function testCommittedOperationManifestMatchesThePackage(string $version): void
    {
        $extractor = $this->extractorOrSkip($version);

        self::assertSame(
            $this->committed(self::MANIFEST_DIR . '/' . $version . '-operations.json'),
            $extractor->buildOperationManifest($version),
            sprintf(
                'The committed %s operation manifest no longer matches the package. Either the package '
                . 'was updated (rerun `php src/Component/CodeGeneration/tests/Fixtures/'
                . 'seed-operation-fixtures.php %s` and review the diff) or the extractor changed.',
                strtoupper($version),
                $version,
            ),
        );
    }

    #[DataProvider('versions')]
    public function testCommittedTypeIndexMatchesThePackage(string $version): void
    {
        $extractor = $this->extractorOrSkip($version);

        self::assertSame(
            $this->committed(self::INDEX_DIR . '/' . $version . '-type-index.json'),
            $extractor->buildTypeIndex($version),
            sprintf('The committed %s type index no longer matches the package.', strtoupper($version)),
        );
    }

    /**
     * The guard: the extractor is reading real packages, not quietly producing nothing.
     *
     * An extractor whose glob stopped matching would return an empty manifest, and comparing empty
     * against empty would pass — except the committed fixtures are non-empty, so the assertions above
     * would fail loudly. This pins the positive case anyway, because a future refactor could make both
     * sides empty at once.
     */
    #[DataProvider('versions')]
    public function testTheExtractorReadsANonTrivialCorpus(string $version): void
    {
        $extractor = $this->extractorOrSkip($version);

        $manifest = $extractor->buildOperationManifest($version);
        $index    = $extractor->buildTypeIndex($version);

        $operations = array_filter($manifest, static fn (array $d): bool => ($d['kind'] ?? null) === 'operation');

        self::assertGreaterThan(40, count($operations), 'Implausibly few kind=operation definitions extracted.');
        self::assertGreaterThan(150, count($index), 'Implausibly few types extracted.');
    }

    /**
     * Skipping is acceptable on a developer machine. In CI it is the defect.
     *
     * These are the ONLY drift detectors for ~14,700 lines of committed manifest and type-index
     * fixtures, and in CI all 9 cases skipped while the leg reported pass — observed on PR #104:
     *
     *     SSSSSSSSS...........NNNNN     25 / 25 (100%)
     *     OK, but there were issues!
     *     Tests: 25, Assertions: 56, PHPUnit Notices: 5, Skipped: 9.
     *
     * `phpunit.dist.xml` sets failOnDeprecation/failOnNotice/failOnWarning but not `failOnSkipped`,
     * and no CI step populates `demo/var/cache/dev/.fhir/`. So the fixtures could go stale after a
     * package bump with no signal at all, and the hand-maintained PRE_REGISTERED literals would
     * become the only authority.
     *
     * Locally the skip is still right — a fresh clone has no package cache and should not fail for
     * it. So the behaviour is split: skip where a human can read the message and act on it, fail
     * where nobody will. The real fix is to prime the cache in the `tests` job, which is a workflow
     * change; until that lands this at least converts a silent pass into a loud failure.
     */
    private function extractorOrSkip(string $version): OperationFixtureExtractor
    {
        $extractor = new OperationFixtureExtractor();

        if (!in_array($version, $extractor->availableVersions(), true)) {
            $message = sprintf(
                'No %s package in demo/var/cache/dev/.fhir/ — run `composer run generate-models-all` to populate it.',
                strtoupper($version),
            );

            // `CI` and `GITHUB_ACTIONS` are both set unconditionally by GitHub Actions runners, and
            // `CI` by essentially every other provider. Checking both so this fires on either.
            if (getenv('CI') !== false || getenv('GITHUB_ACTIONS') !== false) {
                self::fail($message . ' In CI this is a hard failure: skipping here would leave the '
                    . 'committed fixtures with no drift detector at all while the job reported pass.');
            }

            self::markTestSkipped($message);
        }

        return $extractor;
    }

    /**
     * @return array<string, mixed>
     */
    private function committed(string $path): array
    {
        $raw = file_get_contents($path);

        self::assertIsString($raw, sprintf('Committed fixture %s is unreadable.', $path));

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($raw, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}

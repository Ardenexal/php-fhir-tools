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

    private function extractorOrSkip(string $version): OperationFixtureExtractor
    {
        $extractor = new OperationFixtureExtractor();

        if (!in_array($version, $extractor->availableVersions(), true)) {
            self::markTestSkipped(sprintf(
                'No %s package in demo/var/cache/dev/.fhir/ — run `composer run generate-models-all` to populate it.',
                strtoupper($version),
            ));
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

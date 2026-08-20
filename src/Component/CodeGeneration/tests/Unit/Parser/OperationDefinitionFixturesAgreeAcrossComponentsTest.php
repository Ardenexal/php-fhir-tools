<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Parser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The same OperationDefinition is committed twice; the two copies must not drift apart.
 *
 * `CodeGeneration/tests/Fixtures/OperationDefinitions/` and
 * `Serialization/tests/Fixtures/OperationDefinitions/` each carry their own copy of the
 * `CodeSystem-$lookup` definition for R4, R4B and R5. Two components assert against them
 * independently — `AllowedTypeReaderTest` reads the CodeGeneration copy as the *only* oracle for the
 * allowed-type union — and until now nothing checked that the pairs agreed.
 *
 * Neither copy has a producer: `seed-operation-fixtures.php` writes `OperationManifests/` and
 * `TypeIndex/` and nothing else, so these files are hand-maintained. That makes silent divergence a
 * live possibility rather than a theoretical one: someone refreshing one tree after a package bump
 * has no reason to know the other exists, and the result would be two components asserting against
 * different notions of the same definition.
 *
 * This is deliberately NOT a drift check against the FHIR packages — that would need the package
 * cache, which CI does not have (see `OperationFixturesMatchPackagesTest`, whose 9 cases skip there).
 * It is the weaker, always-runnable invariant: whatever these files say, they say the same thing.
 *
 * @see .goat-flow/learning-loop/footguns/operation-allowed-type-sources.md
 */
final class OperationDefinitionFixturesAgreeAcrossComponentsTest extends TestCase
{
    private const string CODEGEN_DIR = __DIR__ . '/../../Fixtures/OperationDefinitions';

    private const string SERIALIZATION_DIR = __DIR__ . '/../../../../Serialization/tests/Fixtures/OperationDefinitions';

    /**
     * @return iterable<string, array{string}>
     */
    public static function duplicatedFixtureProvider(): iterable
    {
        foreach (['r4', 'r4b', 'r5'] as $version) {
            yield $version => [sprintf('%s-CodeSystem-lookup.json', $version)];
        }
    }

    #[DataProvider('duplicatedFixtureProvider')]
    public function testBothComponentsCarryTheSameDefinition(string $filename): void
    {
        $codegen       = self::CODEGEN_DIR . '/' . $filename;
        $serialization = self::SERIALIZATION_DIR . '/' . $filename;

        self::assertFileExists($codegen);
        self::assertFileExists(
            $serialization,
            'One tree has this fixture and the other does not — the pair has already drifted.',
        );

        // Compared as decoded structures, not bytes: the two families differ in formatting (the
        // seeded files end with a newline, these minified copies do not), and that difference is
        // cosmetic. What must match is what the parsers actually see.
        self::assertSame(
            self::decode($codegen),
            self::decode($serialization),
            sprintf(
                '%s differs between the CodeGeneration and Serialization fixture trees. '
                . 'AllowedTypeReaderTest treats the CodeGeneration copy as the sole oracle for the '
                . 'allowed-type union, so a divergence means the two components are asserting '
                . 'against different definitions.',
                $filename,
            ),
        );
    }

    /**
     * The allowed-type union is the thing these fixtures exist to pin, so assert it survives here.
     *
     * `AllowedTypeReaderTest` asserts `allowedType === []` plus exactly seven
     * `operationdefinition-allowed-type` extensions on `property.value`. If a future edit populated
     * `allowedType` in one tree only, the equality test above would catch it — but this makes the
     * *reason* it matters legible at the point of failure rather than leaving a reader to work out
     * why two JSON blobs need to agree.
     */
    #[DataProvider('duplicatedFixtureProvider')]
    public function testTheAllowedTypeSourceIsTheExtensionInBothCopies(string $filename): void
    {
        foreach ([self::CODEGEN_DIR . '/' . $filename, self::SERIALIZATION_DIR . '/' . $filename] as $path) {
            // `use` is part of the address, not decoration: $lookup declares `property` in BOTH
            // directions — an `in` parameter typed `code` and an `out` backbone group — and only the
            // `out` one has the polymorphic `value` part. Matching on name alone finds the `in` one
            // and sees no `part`, which is the in/out collision the generated classes are shaped to
            // make impossible.
            $parameter = self::parameterNamed(self::decode($path), 'property', 'out');
            self::assertNotNull($parameter, sprintf('%s has no `out` `property` parameter.', $path));

            $value = self::parameterNamed($parameter, 'value', 'out');
            self::assertNotNull($value, sprintf('%s has no `property.value` part.', $path));

            self::assertSame(
                [],
                $value['allowedType'] ?? [],
                sprintf(
                    '%s now populates parameter.allowedType. That is good news, but the reader and '
                    . 'the footgun record both assume the extension is the only populated source — '
                    . 'check operation-allowed-type-sources.md before changing the reader.',
                    $path,
                ),
            );

            $extensions = array_filter(
                $value['extension'] ?? [],
                static fn (array $extension): bool => ($extension['url'] ?? null)
                    === 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type',
            );

            self::assertCount(
                7,
                $extensions,
                sprintf('%s: the allowed-type extension count moved off seven.', $path),
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    private static function decode(string $path): array
    {
        $contents = file_get_contents($path);
        self::assertIsString($contents, sprintf('Could not read %s', $path));

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /**
     * Find a named entry in either `parameter[]` (on the definition) or `part[]` (on a parameter).
     *
     * Matches on name AND `use`, because a name alone is not unique within one definition.
     *
     * @param array<string, mixed> $haystack
     *
     * @return array<string, mixed>|null
     */
    private static function parameterNamed(array $haystack, string $name, string $use): ?array
    {
        /** @var list<array<string, mixed>> $candidates */
        $candidates = $haystack['parameter'] ?? $haystack['part'] ?? [];

        foreach ($candidates as $candidate) {
            if (($candidate['name'] ?? null) === $name && ($candidate['use'] ?? null) === $use) {
                return $candidate;
            }
        }

        return null;
    }
}

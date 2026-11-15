<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A parse failure must say where it happened.
 *
 * PHP's `json_last_error_msg()` returns a bare "Syntax error" with no position, which cannot locate a
 * fault in a large Bundle and cannot be attached to an element. seld/jsonlint supplies the position for
 * JSON; libxml already supplied it for XML and we were discarding it.
 *
 * These assert on *position and cause*, never on exact wording — the upstream phrasing belongs to
 * jsonlint and libxml and may legitimately change between versions.
 */
final class ParseErrorDetailTest extends TestCase
{
    private FHIRSerializationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testMalformedJsonReportsTheOffendingLine(): void
    {
        $json = <<<'JSON'
            {
              "resourceType": "Patient",
              "name": [
                { "family": "Simmons" }
                { "family": "Second" }
              ]
            }
            JSON;

        try {
            $this->service->deserialize($json);
            self::fail('Expected malformed JSON to throw');
        } catch (FHIRSerializationException $e) {
            // jsonlint anchors at the token *before* the fault — the object on line 4 that should have
            // been followed by a comma — not at line 5 where the next object begins. That is the
            // convention; what matters is that a line is named at all.
            self::assertStringContainsString('line 4', $e->getMessage());
            self::assertStringNotContainsString(
                'Unable to detect target class',
                $e->getMessage(),
                'A parse failure must not be reported as a type-resolution failure',
            );
        }
    }

    public function testMalformedJsonNoLongerReportsABarePhpSyntaxError(): void
    {
        try {
            $this->service->deserialize('{ resourceType : "Patient" }');
            self::fail('Expected unquoted property name to throw');
        } catch (FHIRSerializationException $e) {
            // "Syntax error" is precisely the positionless message jsonlint replaces.
            self::assertNotSame('Syntax error', $e->getMessage());
            self::assertStringContainsString('line 1', $e->getMessage());
        }
    }

    public function testMalformedXmlReportsLineAndColumn(): void
    {
        $xml = "<Patient xmlns=\"http://hl7.org/fhir\">\n  <id value=\"x\">\n</Patient>";

        try {
            $this->service->deserialize($xml);
            self::fail('Expected mismatched tags to throw');
        } catch (FHIRSerializationException $e) {
            self::assertStringContainsString('line', $e->getMessage());
            self::assertStringContainsString('column', $e->getMessage());
        }
    }

    public function testUndetectableFormatShowsWhatItReceived(): void
    {
        // A leading JSON comment is the real-world case: neither "{" nor "<", so format detection
        // cannot classify it, and the old message named no cause at all.
        try {
            $this->service->deserialize("// a comment\n{\"resourceType\":\"Patient\"}");
            self::fail('Expected undetectable format to throw');
        } catch (FHIRSerializationException $e) {
            self::assertStringContainsString('// a comment', $e->getMessage());
        }
    }

    public function testEmptyInputIsReportedAsEmptyRatherThanUndetectable(): void
    {
        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/input is empty/');

        $this->service->deserialize('   ');
    }

    /**
     * Every manifest a consumer can install through must declare seld/jsonlint.
     *
     * The failure this guards is invisible to every other test. Symfony throws UnsupportedException
     * when `json_decode_detailed_errors` is set and the class is missing; because phpbench pulls
     * seld/jsonlint in as a *dev* dependency of the monorepo, the entire suite stays green while a
     * published consumer breaks on the first malformed payload.
     *
     * Both manifests matter, and they drift independently — this repo splits into per-component
     * packages, so `ardenexal/fhir-serialization` is what a consumer of this class actually installs,
     * while the root package is what a monorepo consumer installs. The root `composer.lock` is
     * gitignored, so these declarations are the only tracked statement of the requirement.
     *
     * @return array<string, array{string}>
     */
    public static function manifestProvider(): array
    {
        return [
            'root package'            => [__DIR__ . '/../../../../../composer.json'],
            'serialization component' => [__DIR__ . '/../../composer.json'],
        ];
    }

    #[DataProvider('manifestProvider')]
    public function testJsonLintIsAProductionDependency(string $manifestPath): void
    {
        self::assertFileExists($manifestPath);

        $composer = json_decode((string) file_get_contents($manifestPath), true);

        self::assertIsArray($composer);
        self::assertArrayHasKey(
            'seld/jsonlint',
            $composer['require'] ?? [],
            sprintf('%s must declare seld/jsonlint in "require", not "require-dev"', basename(dirname($manifestPath))),
        );
    }
}

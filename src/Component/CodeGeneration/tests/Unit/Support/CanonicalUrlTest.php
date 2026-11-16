<?php

declare(strict_types=1);

/**
 * Guards the canonical-URL version strip that keeps generated class names loadable.
 */

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Support;

use Ardenexal\FHIRTools\Component\CodeGeneration\Support\CanonicalUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Covers stripping the `|<version>` suffix that published IGs attach to canonical URLs.
 */
class CanonicalUrlTest extends TestCase
{
    /**
     * Canonical URLs paired with the bare URL they must reduce to.
     *
     * @return array<string, array{string, string}> case name → [input URL, expected bare URL]
     */
    public static function urlProvider(): array
    {
        return [
            'versioned StructureDefinition' => [
                'http://hl7.org/fhir/StructureDefinition/Endpoint|4.0.1',
                'http://hl7.org/fhir/StructureDefinition/Endpoint',
            ],
            'versioned ValueSet' => [
                'http://hl7.org/fhir/ValueSet/administrative-gender|5.0.0',
                'http://hl7.org/fhir/ValueSet/administrative-gender',
            ],
            'unversioned is unchanged' => [
                'http://hl7.org/fhir/StructureDefinition/Endpoint',
                'http://hl7.org/fhir/StructureDefinition/Endpoint',
            ],
            'pre-release version suffix' => [
                'http://hl7.org/fhir/StructureDefinition/Foo|1.0.0-ballot',
                'http://hl7.org/fhir/StructureDefinition/Foo',
            ],
            'empty string'                  => ['', ''],
            'trailing pipe yields bare url' => [
                'http://example.org/fhir/StructureDefinition/Bar|',
                'http://example.org/fhir/StructureDefinition/Bar',
            ],
        ];
    }

    /**
     * A version suffix is removed; a URL without one survives untouched.
     *
     * @param string $input    canonical URL as a published IG might write it
     * @param string $expected bare URL the strip must produce
     *
     * @return void
     */
    #[DataProvider('urlProvider')]
    public function testStripVersion(string $input, string $expected): void
    {
        self::assertSame($expected, CanonicalUrl::stripVersion($input));
    }

    /**
     * The bug this helper exists to prevent: `Endpoint|4.0.1` pascal-cases to `Endpoint401`, so an
     * unstripped canonical produced the non-existent class `…\Resource\Endpoint401Resource`.
     * Stripping first yields `Endpoint`, whose class does exist.
     *
     * @return void
     */
    public function testStrippedSegmentYieldsALoadableResourceClass(): void
    {
        $bare    = CanonicalUrl::stripVersion('http://hl7.org/fhir/StructureDefinition/Endpoint|4.0.1');
        $segment = substr($bare, (int) strrpos($bare, '/') + 1);

        self::assertSame('Endpoint', $segment);
        self::assertTrue(
            class_exists("Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\{$segment}Resource"),
            'Stripped segment must name a real generated resource class',
        );
    }
}

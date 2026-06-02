<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\ObligationExtensionParser;
use PHPUnit\Framework\TestCase;

/**
 * Verifies ObligationExtensionParser correctly extracts obligation codes from element extension arrays.
 */
class ObligationExtensionParserTest extends TestCase
{
    private ObligationExtensionParser $parser;

    protected function setUp(): void
    {
        $this->parser = new ObligationExtensionParser();
    }

    public function testParseSingleObligationWithCode(): void
    {
        $extensions = [
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'code', 'valueCode' => 'SHALL:populate'],
                ],
            ],
        ];

        $result = $this->parser->parse($extensions);

        self::assertCount(1, $result);
        self::assertSame('SHALL:populate', $result[0]['code']);
        self::assertNull($result[0]['actor']);
        self::assertNull($result[0]['filter']);
        self::assertNull($result[0]['documentation']);
    }

    public function testParseSingleObligationWithCodeAndActor(): void
    {
        $extensions = [
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'code',  'valueCode'      => 'SHALL:populate'],
                    ['url' => 'actor', 'valueCanonical' => 'http://example.org/actor/requester'],
                ],
            ],
        ];

        $result = $this->parser->parse($extensions);

        self::assertCount(1, $result);
        self::assertSame('SHALL:populate', $result[0]['code']);
        self::assertSame('http://example.org/actor/requester', $result[0]['actor']);
        self::assertNull($result[0]['filter']);
    }

    public function testParseMultipleObligations(): void
    {
        $extensions = [
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'code', 'valueCode' => 'SHALL:populate'],
                    ['url' => 'actor', 'valueCanonical' => 'http://example.org/actor/sender'],
                ],
            ],
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'code', 'valueCode' => 'SHOULD:display'],
                ],
            ],
        ];

        $result = $this->parser->parse($extensions);

        self::assertCount(2, $result);
        self::assertSame('SHALL:populate', $result[0]['code']);
        self::assertSame('http://example.org/actor/sender', $result[0]['actor']);
        self::assertSame('SHOULD:display', $result[1]['code']);
        self::assertNull($result[1]['actor']);
    }

    public function testSkipsMissingCodeSubExtension(): void
    {
        $extensions = [
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'actor', 'valueCanonical' => 'http://example.org/actor/requester'],
                    // code is deliberately missing
                ],
            ],
        ];

        $result = @$this->parser->parse($extensions); // suppress E_USER_NOTICE for missing code

        self::assertEmpty($result, 'Obligation without code sub-extension must be skipped');
    }

    public function testIgnoresNonObligationExtensions(): void
    {
        $extensions = [
            [
                'url'         => 'http://hl7.org/fhir/StructureDefinition/must-support-reason',
                'valueString' => 'some reason',
            ],
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'code', 'valueCode' => 'SHALL:handle'],
                ],
            ],
        ];

        $result = $this->parser->parse($extensions);

        self::assertCount(1, $result);
        self::assertSame('SHALL:handle', $result[0]['code']);
    }

    public function testReturnsEmptyArrayWhenNoObligations(): void
    {
        $result = $this->parser->parse([]);
        self::assertEmpty($result);
    }

    public function testParseWithDocumentation(): void
    {
        $extensions = [
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'code',          'valueCode'     => 'SHALL:populate'],
                    ['url' => 'documentation', 'valueMarkdown' => 'Must be populated for interop.'],
                ],
            ],
        ];

        $result = $this->parser->parse($extensions);

        self::assertCount(1, $result);
        self::assertSame('Must be populated for interop.', $result[0]['documentation']);
    }

    public function testParseWithFilter(): void
    {
        $extensions = [
            [
                'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                'extension' => [
                    ['url' => 'code',   'valueCode'   => 'SHALL:populate'],
                    ['url' => 'filter', 'valueString' => 'status != "cancelled"'],
                ],
            ],
        ];

        $result = $this->parser->parse($extensions);

        self::assertCount(1, $result);
        self::assertSame('status != "cancelled"', $result[0]['filter']);
    }
}

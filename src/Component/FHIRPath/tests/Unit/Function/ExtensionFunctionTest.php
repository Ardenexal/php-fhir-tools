<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the FHIR extension() function.
 *
 * @author FHIR Tools Contributors
 */
final class ExtensionFunctionTest extends TestCase
{
    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
    }

    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    // -------------------------------------------------------------------------
    // Array-based resources (plain PHP arrays from json_decode)
    // -------------------------------------------------------------------------

    public function testMatchesSingleExtensionByUrlOnArray(): void
    {
        $resource = [
            'resourceType' => 'Patient',
            'extension'    => [
                ['url' => 'http://example.com/ext/race', 'valueString' => 'white'],
                ['url' => 'http://example.com/ext/ethnicity', 'valueString' => 'hispanic'],
            ],
        ];

        $result = $this->evaluate("extension('http://example.com/ext/race')", $resource);

        self::assertSame(1, $result->count());
        $ext = $result->first();
        self::assertIsArray($ext);
        self::assertSame('http://example.com/ext/race', $ext['url']);
        self::assertSame('white', $ext['valueString']);
    }

    public function testMatchesMultipleExtensionsWithSameUrl(): void
    {
        $resource = [
            'extension' => [
                ['url' => 'http://example.com/ext/tag', 'valueString' => 'a'],
                ['url' => 'http://example.com/ext/tag', 'valueString' => 'b'],
                ['url' => 'http://example.com/ext/other', 'valueString' => 'c'],
            ],
        ];

        $result = $this->evaluate("extension('http://example.com/ext/tag')", $resource);

        self::assertSame(2, $result->count());
    }

    public function testReturnsEmptyWhenUrlNotFound(): void
    {
        $resource = [
            'extension' => [
                ['url' => 'http://example.com/ext/a', 'valueString' => 'x'],
            ],
        ];

        self::assertTrue(
            $this->evaluate("extension('http://example.com/ext/notfound')", $resource)->isEmpty(),
        );
    }

    public function testReturnsEmptyWhenNoExtensionProperty(): void
    {
        $resource = ['name' => 'Alice'];

        self::assertTrue($this->evaluate("extension('http://example.com/ext/a')", $resource)->isEmpty());
    }

    public function testReturnsEmptyForEmptyInput(): void
    {
        self::assertTrue($this->evaluate("{}.extension('http://example.com/ext/a')", null)->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Object-based resources
    // -------------------------------------------------------------------------

    public function testMatchesExtensionOnObjectWithPublicProperty(): void
    {
        $ext1            = new \stdClass();
        $ext1->url       = 'http://example.com/ext/race';
        $ext1->valueCode = 'asian';

        $ext2      = new \stdClass();
        $ext2->url = 'http://example.com/ext/other';

        $resource            = new \stdClass();
        $resource->extension = [$ext1, $ext2];

        $result = $this->evaluate("extension('http://example.com/ext/race')", $resource);

        self::assertSame(1, $result->count());
        $matched = $result->first();
        self::assertSame('asian', $matched->valueCode);
    }

    public function testMatchesExtensionOnObjectWithGetterMethod(): void
    {
        $ext = new class ('http://example.com/ext/birth', 'yes') {
            private string $url;

            private string $valueString;

            public function __construct(string $url, string $valueString)
            {
                $this->url         = $url;
                $this->valueString = $valueString;
            }

            public function getUrl(): string
            {
                return $this->url;
            }

            public function getValueString(): string
            {
                return $this->valueString;
            }
        };

        $resource = new class ([$ext]) {
            /** @var array<int, mixed> */
            private array $extension;

            /** @param array<int, mixed> $extension */
            public function __construct(array $extension)
            {
                $this->extension = $extension;
            }

            /** @return array<int, mixed> */
            public function getExtension(): array
            {
                return $this->extension;
            }
        };

        $result = $this->evaluate("extension('http://example.com/ext/birth')", $resource);

        self::assertSame(1, $result->count());
    }

    // -------------------------------------------------------------------------
    // Navigation chaining
    // -------------------------------------------------------------------------

    public function testExtensionChainedOnNestedProperty(): void
    {
        // patient.name[0].extension('url')
        $resource = [
            'name' => [
                [
                    'use'       => 'official',
                    'extension' => [
                        ['url' => 'http://example.com/ext/prefix', 'valueString' => 'Dr'],
                    ],
                ],
            ],
        ];

        $result = $this->evaluate("name.extension('http://example.com/ext/prefix')", $resource);

        self::assertSame(1, $result->count());
        $ext = $result->first();
        self::assertSame('Dr', $ext['valueString']);
    }

    public function testExtensionUrlIsCaseSensitive(): void
    {
        $resource = [
            'extension' => [
                ['url' => 'http://example.com/ext/Race', 'valueString' => 'white'],
            ],
        ];

        // Different case → no match
        self::assertTrue(
            $this->evaluate("extension('http://example.com/ext/race')", $resource)->isEmpty(),
        );

        // Exact case → match
        self::assertSame(
            1,
            $this->evaluate("extension('http://example.com/ext/Race')", $resource)->count(),
        );
    }

    public function testExtensionValueCanBeAccessedOnResult(): void
    {
        $resource = [
            'extension' => [
                ['url' => 'http://example.com/ext/score', 'valueInteger' => 42],
            ],
        ];

        // Chain: extension('url').valueInteger → [42]
        $result = $this->evaluate("extension('http://example.com/ext/score').valueInteger", $resource);

        self::assertSame([42], $result->toArray());
    }

    public function testExtensionOnMultipleInputItems(): void
    {
        // Input collection has two items; extension() should apply to each
        $resource = [
            'items' => [
                [
                    'extension' => [
                        ['url' => 'http://example.com/ext/a', 'valueString' => 'first'],
                    ],
                ],
                [
                    'extension' => [
                        ['url' => 'http://example.com/ext/a', 'valueString' => 'second'],
                    ],
                ],
            ],
        ];

        $result = $this->evaluate("items.extension('http://example.com/ext/a')", $resource);

        self::assertSame(2, $result->count());
    }
}

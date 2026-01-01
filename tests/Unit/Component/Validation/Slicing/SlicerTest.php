<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\Slicing;

use Ardenexal\FHIRTools\Component\Validation\Slicing\Slicer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * @covers \Ardenexal\FHIRTools\Component\Validation\Slicing\Slicer
 */
final class SlicerTest extends TestCase
{
    private Slicer $slicer;

    private ArrayAdapter $cache;

    protected function setUp(): void
    {
        $this->cache  = new ArrayAdapter();
        $this->slicer = new Slicer($this->cache);
    }

    public function testMatchEmptyElementsReturnsEmptyArray(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'system']],
            'slices'        => [
                'slice1' => ['discriminatorValue' => ['system' => 'http://example.org']],
            ],
        ];

        $result = $this->slicer->match(
            [],
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertSame([], $result);
    }

    public function testMatchNoSlicesReturnsUnmatched(): void
    {
        $elements = [
            ['system' => 'http://example.org', 'value' => '123'],
        ];

        $result = $this->slicer->match(
            $elements,
            [],
            'http://profile',
            'Patient.identifier',
        );

        self::assertArrayHasKey('__unmatched', $result);
        self::assertCount(1, $result['__unmatched']);
    }

    public function testMatchValueDiscriminatorSuccess(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'system']],
            'slices'        => [
                'npiSlice' => [
                    'discriminatorValue' => ['system' => 'http://hl7.org/fhir/sid/us-npi'],
                ],
                'ssnSlice' => [
                    'discriminatorValue' => ['system' => 'http://hl7.org/fhir/sid/us-ssn'],
                ],
            ],
        ];

        $elements = [
            ['system' => 'http://hl7.org/fhir/sid/us-npi', 'value' => '1234567893'],
            ['system' => 'http://hl7.org/fhir/sid/us-ssn', 'value' => '123-45-6789'],
            ['system' => 'http://example.org', 'value' => 'other'],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertArrayHasKey('npiSlice', $result);
        self::assertCount(1, $result['npiSlice']);
        self::assertSame('http://hl7.org/fhir/sid/us-npi', $result['npiSlice'][0]['system']);

        self::assertArrayHasKey('ssnSlice', $result);
        self::assertCount(1, $result['ssnSlice']);
        self::assertSame('http://hl7.org/fhir/sid/us-ssn', $result['ssnSlice'][0]['system']);

        self::assertArrayHasKey('__unmatched', $result);
        self::assertCount(1, $result['__unmatched']);
    }

    public function testMatchPatternDiscriminatorSuccess(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'pattern', 'path' => 'coding']],
            'slices'        => [
                'loincSlice' => [
                    'discriminatorPattern' => [
                        'coding' => ['system' => 'http://loinc.org'],
                    ],
                ],
            ],
        ];

        $elements = [
            ['coding' => ['system' => 'http://loinc.org', 'code' => '1234-5']],
            ['coding' => ['system' => 'http://snomed.info/sct', 'code' => '999']],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Observation.code.coding',
        );

        self::assertArrayHasKey('loincSlice', $result);
        self::assertCount(1, $result['loincSlice']);
    }

    public function testMatchTypeDiscriminatorSuccess(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'type', 'path' => 'value[x]']],
            'slices'        => [
                'stringSlice' => [
                    'discriminatorType' => ['value[x]' => 'string'],
                ],
            ],
        ];

        $elements = [
            ['valueString' => 'text value'],
            ['valueInteger' => 42],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Observation.value[x]',
        );

        self::assertArrayHasKey('stringSlice', $result);
        self::assertCount(1, $result['stringSlice']);
    }

    public function testMatchExistsDiscriminatorSuccess(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'exists', 'path' => 'use']],
            'slices'        => [
                'withUseSlice' => [
                    'discriminatorExists' => ['use' => true],
                ],
            ],
        ];

        $elements = [
            ['system' => 'http://example.org', 'use' => 'official'],
            ['system' => 'http://example.org'],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertArrayHasKey('withUseSlice', $result);
        self::assertCount(1, $result['withUseSlice']);
    }

    public function testMatchFirstMatchWins(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'system']],
            'slices'        => [
                'slice1' => [
                    'discriminatorValue' => ['system' => 'http://example.org'],
                ],
                'slice2' => [
                    'discriminatorValue' => ['system' => 'http://example.org'],
                ],
            ],
        ];

        $elements = [
            ['system' => 'http://example.org', 'value' => '123'],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertArrayHasKey('slice1', $result);
        self::assertCount(1, $result['slice1']);
        self::assertArrayNotHasKey('slice2', $result);
    }

    public function testMatchOpenSlicingRemovesEmptyUnmatched(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'system']],
            'rules'         => 'open',
            'slices'        => [
                'slice1' => [
                    'discriminatorValue' => ['system' => 'http://example.org'],
                ],
            ],
        ];

        $elements = [
            ['system' => 'http://example.org', 'value' => '123'],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertArrayNotHasKey('__unmatched', $result);
    }

    public function testMatchClosedSlicingKeepsUnmatched(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'system']],
            'rules'         => 'closed',
            'slices'        => [
                'slice1' => [
                    'discriminatorValue' => ['system' => 'http://example.org'],
                ],
            ],
        ];

        $elements = [
            ['system' => 'http://example.org', 'value' => '123'],
            ['system' => 'http://other.org', 'value' => '456'],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertArrayHasKey('__unmatched', $result);
        self::assertCount(1, $result['__unmatched']);
    }

    public function testValidateCardinalityMinViolation(): void
    {
        $slicingDefinition = [
            'slices' => [
                'requiredSlice' => [
                    'min' => 1,
                    'max' => '*',
                ],
            ],
        ];

        $buckets = [
            'requiredSlice' => [], // Empty, violates min=1
        ];

        $issues = $this->slicer->validateCardinality($buckets, $slicingDefinition);

        self::assertCount(1, $issues);
        self::assertSame('requiredSlice', $issues[0]['slice']);
        self::assertSame('min_cardinality', $issues[0]['violation']);
        self::assertSame(1, $issues[0]['min']);
        self::assertSame(0, $issues[0]['actual']);
    }

    public function testValidateCardinalityMaxViolation(): void
    {
        $slicingDefinition = [
            'slices' => [
                'limitedSlice' => [
                    'min' => 0,
                    'max' => '1',
                ],
            ],
        ];

        $buckets = [
            'limitedSlice' => ['element1', 'element2'], // Two elements, violates max=1
        ];

        $issues = $this->slicer->validateCardinality($buckets, $slicingDefinition);

        self::assertCount(1, $issues);
        self::assertSame('limitedSlice', $issues[0]['slice']);
        self::assertSame('max_cardinality', $issues[0]['violation']);
        self::assertSame('1', $issues[0]['max']);
        self::assertSame(2, $issues[0]['actual']);
    }

    public function testValidateCardinalityClosedSlicingViolation(): void
    {
        $slicingDefinition = [
            'rules'  => 'closed',
            'slices' => [
                'slice1' => ['min' => 0, 'max' => '*'],
            ],
        ];

        $buckets = [
            'slice1'      => ['element1'],
            '__unmatched' => ['unmatchedElement'],
        ];

        $issues = $this->slicer->validateCardinality($buckets, $slicingDefinition);

        self::assertCount(1, $issues);
        self::assertSame('__unmatched', $issues[0]['slice']);
        self::assertSame('closed_slicing', $issues[0]['violation']);
        self::assertSame(1, $issues[0]['actual']);
    }

    public function testValidateCardinalityNoViolations(): void
    {
        $slicingDefinition = [
            'slices' => [
                'slice1' => ['min' => 1, 'max' => '2'],
            ],
        ];

        $buckets = [
            'slice1' => ['element1'],
        ];

        $issues = $this->slicer->validateCardinality($buckets, $slicingDefinition);

        self::assertCount(0, $issues);
    }

    public function testCacheHitReturnsSameMatcher(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'system']],
            'slices'        => [
                'slice1' => [
                    'discriminatorValue' => ['system' => 'http://example.org'],
                ],
            ],
        ];

        $elements = [['system' => 'http://example.org']];

        // First call - cache miss
        $result1 = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        // Second call - cache hit
        $result2 = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertSame($result1, $result2);
        self::assertTrue($this->slicer->isCached('http://profile', 'Patient.identifier', 'slice1'));
    }

    public function testClearCacheRemovesCachedMatchers(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'system']],
            'slices'        => [
                'slice1' => [
                    'discriminatorValue' => ['system' => 'http://example.org'],
                ],
            ],
        ];

        $elements = [['system' => 'http://example.org']];

        // Populate cache
        $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertTrue($this->slicer->isCached('http://profile', 'Patient.identifier', 'slice1'));

        // Clear cache
        $this->slicer->clearCache();

        self::assertFalse($this->slicer->isCached('http://profile', 'Patient.identifier', 'slice1'));
    }

    public function testMultipleDiscriminatorsWithAndLogic(): void
    {
        $slicingDefinition = [
            'discriminator' => [
                ['type' => 'value', 'path' => 'system'],
                ['type' => 'value', 'path' => 'use'],
            ],
            'slices' => [
                'officialNpiSlice' => [
                    'discriminatorValue' => [
                        'system' => 'http://hl7.org/fhir/sid/us-npi',
                        'use'    => 'official',
                    ],
                ],
            ],
        ];

        $elements = [
            ['system' => 'http://hl7.org/fhir/sid/us-npi', 'use' => 'official', 'value' => '123'],
            ['system' => 'http://hl7.org/fhir/sid/us-npi', 'use' => 'temp', 'value' => '456'],
            ['system' => 'http://other.org', 'use' => 'official', 'value' => '789'],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Patient.identifier',
        );

        self::assertArrayHasKey('officialNpiSlice', $result);
        self::assertCount(1, $result['officialNpiSlice']); // Only first matches both discriminators
        self::assertArrayHasKey('__unmatched', $result);
        self::assertCount(2, $result['__unmatched']);
    }

    public function testNestedPathResolution(): void
    {
        $slicingDefinition = [
            'discriminator' => [['type' => 'value', 'path' => 'coding.system']],
            'slices'        => [
                'loincSlice' => [
                    'discriminatorValue' => ['coding.system' => 'http://loinc.org'],
                ],
            ],
        ];

        $elements = [
            ['coding' => ['system' => 'http://loinc.org', 'code' => '1234-5']],
        ];

        $result = $this->slicer->match(
            $elements,
            $slicingDefinition,
            'http://profile',
            'Observation.code',
        );

        self::assertArrayHasKey('loincSlice', $result);
        self::assertCount(1, $result['loincSlice']);
    }
}

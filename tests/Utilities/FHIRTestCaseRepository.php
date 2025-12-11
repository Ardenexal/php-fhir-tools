<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Utilities;

/**
 * Repository for official FHIR test cases integration
 *
 * This class provides integration with official FHIR test cases
 * from the FHIR specification repository for comprehensive testing.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class FHIRTestCaseRepository
{
    private const FHIR_TEST_CASES_URL = 'https://github.com/HL7/fhir-test-cases';

    private const CACHE_DIR = 'tests/cache/fhir-test-cases';

    private string $cacheDir;

    public function __construct(?string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?? self::CACHE_DIR;
        $this->ensureCacheDirectory();
    }

    /**
     * Get official FHIR test cases for a specific resource type
     */
    public function getTestCasesForResource(string $resourceType, string $fhirVersion = 'R4B'): array
    {
        $cacheFile = $this->getCacheFile($resourceType, $fhirVersion);

        if ($this->isCacheValid($cacheFile)) {
            return $this->loadFromCache($cacheFile);
        }

        $testCases = $this->downloadTestCases($resourceType, $fhirVersion);
        $this->saveToCache($cacheFile, $testCases);

        return $testCases;
    }

    /**
     * Get validation test cases for StructureDefinitions
     */
    public function getValidationTestCases(string $fhirVersion = 'R4B'): array
    {
        return [
            'valid'   => $this->getValidStructureDefinitions($fhirVersion),
            'invalid' => $this->getInvalidStructureDefinitions($fhirVersion),
        ];
    }

    /**
     * Get performance test cases for large FHIR packages
     */
    public function getPerformanceTestCases(): array
    {
        return [
            'small_package'  => $this->generateSmallPackage(),
            'medium_package' => $this->generateMediumPackage(),
            'large_package'  => $this->generateLargePackage(),
        ];
    }

    /**
     * Get edge case test data
     */
    public function getEdgeCaseTestData(): array
    {
        return [
            'empty_elements'      => $this->getEmptyElementTestCases(),
            'complex_cardinality' => $this->getComplexCardinalityTestCases(),
            'deep_nesting'        => $this->getDeepNestingTestCases(),
            'unicode_content'     => $this->getUnicodeTestCases(),
            'large_content'       => $this->getLargeContentTestCases(),
        ];
    }

    /**
     * Ensure cache directory exists
     */
    private function ensureCacheDirectory(): void
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Get cache file path for resource type and version
     */
    private function getCacheFile(string $resourceType, string $fhirVersion): string
    {
        return $this->cacheDir . "/{$fhirVersion}_{$resourceType}.json";
    }

    /**
     * Check if cache file is valid (not older than 24 hours)
     */
    private function isCacheValid(string $cacheFile): bool
    {
        if (!file_exists($cacheFile)) {
            return false;
        }

        $cacheAge = time() - filemtime($cacheFile);

        return $cacheAge < 86400; // 24 hours
    }

    /**
     * Load test cases from cache
     */
    private function loadFromCache(string $cacheFile): array
    {
        $content = file_get_contents($cacheFile);

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Save test cases to cache
     */
    private function saveToCache(string $cacheFile, array $testCases): void
    {
        $content = json_encode($testCases, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        file_put_contents($cacheFile, $content);
    }

    /**
     * Download test cases from FHIR repository (mock implementation)
     */
    private function downloadTestCases(string $resourceType, string $fhirVersion): array
    {
        // In a real implementation, this would download from the FHIR test repository
        // For now, return mock test cases
        return $this->generateMockTestCases($resourceType, $fhirVersion);
    }

    /**
     * Generate mock test cases for testing
     */
    private function generateMockTestCases(string $resourceType, string $fhirVersion): array
    {
        return [
            'valid_examples' => [
                [
                    'name'             => "Valid {$resourceType} Example 1",
                    'resource'         => $this->generateValidResource($resourceType),
                    'expected_outcome' => 'success',
                ],
                [
                    'name'             => "Valid {$resourceType} Example 2",
                    'resource'         => $this->generateValidResource($resourceType),
                    'expected_outcome' => 'success',
                ],
            ],
            'invalid_examples' => [
                [
                    'name'             => "Invalid {$resourceType} - Missing Required Field",
                    'resource'         => $this->generateInvalidResource($resourceType, 'missing_required'),
                    'expected_outcome' => 'validation_error',
                    'expected_errors'  => ['Missing required field'],
                ],
                [
                    'name'             => "Invalid {$resourceType} - Invalid Cardinality",
                    'resource'         => $this->generateInvalidResource($resourceType, 'invalid_cardinality'),
                    'expected_outcome' => 'validation_error',
                    'expected_errors'  => ['Invalid cardinality'],
                ],
            ],
        ];
    }

    /**
     * Generate valid resource for testing
     */
    private function generateValidResource(string $resourceType): array
    {
        $baseResource = [
            'resourceType' => $resourceType,
            'id'           => 'test-' . strtolower($resourceType),
            'meta'         => [
                'versionId'   => '1',
                'lastUpdated' => '2024-01-01T00:00:00Z',
            ],
        ];

        // Add resource-specific required fields
        switch ($resourceType) {
            case 'Patient':
                $baseResource['name'] = [
                    [
                        'family' => 'TestFamily',
                        'given'  => ['TestGiven'],
                    ],
                ];
                break;
            case 'Observation':
                $baseResource['status'] = 'final';
                $baseResource['code']   = [
                    'coding' => [
                        [
                            'system'  => 'http://loinc.org',
                            'code'    => '15074-8',
                            'display' => 'Glucose',
                        ],
                    ],
                ];
                break;
        }

        return $baseResource;
    }

    /**
     * Generate invalid resource for testing
     */
    private function generateInvalidResource(string $resourceType, string $errorType): array
    {
        $resource = $this->generateValidResource($resourceType);

        switch ($errorType) {
            case 'missing_required':
                // Remove required fields based on resource type
                if ($resourceType === 'Patient') {
                    unset($resource['name']);
                } elseif ($resourceType === 'Observation') {
                    unset($resource['status']);
                }
                break;
            case 'invalid_cardinality':
                // Add invalid cardinality violations
                if ($resourceType === 'Patient') {
                    $resource['name'] = []; // Empty array when min=1
                }
                break;
        }

        return $resource;
    }

    /**
     * Get valid StructureDefinitions for testing
     */
    private function getValidStructureDefinitions(string $fhirVersion): array
    {
        return [
            'patient_profile'     => $this->loadFixture('Patient.json'),
            'observation_profile' => $this->loadFixture('Observation.json'),
        ];
    }

    /**
     * Get invalid StructureDefinitions for testing
     */
    private function getInvalidStructureDefinitions(string $fhirVersion): array
    {
        return [
            'invalid_cardinality' => $this->loadFixture('InvalidStructureDefinition.json'),
        ];
    }

    /**
     * Load fixture file
     */
    private function loadFixture(string $filename): array
    {
        $path = __DIR__ . '/../Fixtures/StructureDefinitions/' . $filename;
        if (!file_exists($path)) {
            throw new \RuntimeException("Fixture file not found: {$filename}");
        }

        $content = file_get_contents($path);

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Generate small package for performance testing
     */
    private function generateSmallPackage(): array
    {
        return [
            'name'      => 'test.small.package',
            'version'   => '1.0.0',
            'resources' => array_fill(0, 10, $this->generateValidResource('Patient')),
        ];
    }

    /**
     * Generate medium package for performance testing
     */
    private function generateMediumPackage(): array
    {
        return [
            'name'      => 'test.medium.package',
            'version'   => '1.0.0',
            'resources' => array_fill(0, 100, $this->generateValidResource('Patient')),
        ];
    }

    /**
     * Generate large package for performance testing
     */
    private function generateLargePackage(): array
    {
        return [
            'name'      => 'test.large.package',
            'version'   => '1.0.0',
            'resources' => array_fill(0, 1000, $this->generateValidResource('Patient')),
        ];
    }

    /**
     * Get empty element test cases
     */
    private function getEmptyElementTestCases(): array
    {
        return [
            'empty_string_elements' => ['name' => '', 'value' => ''],
            'null_elements'         => ['name' => null, 'value' => null],
            'empty_arrays'          => ['items' => [], 'values' => []],
        ];
    }

    /**
     * Get complex cardinality test cases
     */
    private function getComplexCardinalityTestCases(): array
    {
        return [
            'zero_to_one'    => ['min' => 0, 'max' => '1'],
            'one_to_one'     => ['min' => 1, 'max' => '1'],
            'zero_to_many'   => ['min' => 0, 'max' => '*'],
            'one_to_many'    => ['min' => 1, 'max' => '*'],
            'specific_range' => ['min' => 2, 'max' => '5'],
        ];
    }

    /**
     * Get deep nesting test cases
     */
    private function getDeepNestingTestCases(): array
    {
        $deepStructure = [];
        $current       = &$deepStructure;

        for ($i = 0; $i < 20; ++$i) {
            $current['level' . $i] = [];
            $current               = &$current['level' . $i];
        }

        $current['value'] = 'deep_value';

        return ['deep_structure' => $deepStructure];
    }

    /**
     * Get Unicode test cases
     */
    private function getUnicodeTestCases(): array
    {
        return [
            'emoji'        => ['name' => '👨‍⚕️ Dr. Smith', 'note' => '🏥 Hospital visit'],
            'multilingual' => [
                'name_en' => 'Patient',
                'name_zh' => '患者',
                'name_ar' => 'مريض',
                'name_ru' => 'Пациент',
            ],
            'special_chars' => ['name' => 'O\'Reilly-Smith', 'note' => 'Special chars: @#$%^&*()'],
        ];
    }

    /**
     * Get large content test cases
     */
    private function getLargeContentTestCases(): array
    {
        return [
            'large_text'   => ['description' => str_repeat('Lorem ipsum dolor sit amet. ', 1000)],
            'large_array'  => ['items' => array_fill(0, 1000, 'item')],
            'large_object' => array_fill_keys(range(0, 999), 'value'),
        ];
    }
}

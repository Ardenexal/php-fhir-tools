<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Tests\Unit;

use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for strict types compliance in Models component
 *
 * @author FHIR Tools Contributors
 */
class StrictTypesCompliancePropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Feature: fhir-models-component, Property 5: Code quality standards compliance (strict types)
     *
     * @test
     */
    public function testAllGeneratedModelsHaveStrictTypesDeclaration(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
        )->then(function(string $version, string $type) {
            $basePath = __DIR__ . '/../../../../src/Component/Models/src/' . $version . '/' . $type;

            if (!is_dir($basePath)) {
                // Skip if directory doesn't exist for this version/type combination
                return;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            );

            foreach ($iterator as $file) {
                if ($file->getExtension() === 'php') {
                    $content = file_get_contents($file->getPathname());

                    // Check that the file starts with <?php
                    self::assertStringStartsWith(
                        '<?php',
                        $content,
                        "File {$file->getPathname()} must start with <?php",
                    );

                    // Check that declare(strict_types=1) is present within first 5 lines
                    $lines                 = explode("\n", $content);
                    $foundInFirstFiveLines = false;
                    for ($i = 0; $i < min(5, count($lines)); ++$i) {
                        if (str_contains($lines[$i], 'declare(strict_types=1)')) {
                            $foundInFirstFiveLines = true;
                            break;
                        }
                    }

                    self::assertTrue(
                        $foundInFirstFiveLines,
                        "File {$file->getPathname()} must have strict types declaration within first 5 lines",
                    );
                }
            }
        });
    }

    /**
     * Test that utility classes also have strict types (different format is acceptable)
     *
     * @test
     */
    public function testUtilityClassesHaveStrictTypesDeclaration(): void
    {
        $utilityPath = __DIR__ . '/../../../../src/Component/Models/src/Utility';

        if (!is_dir($utilityPath)) {
            self::markTestSkipped('Utility directory does not exist');
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($utilityPath, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());

                // Check that the file contains declare(strict_types=1) somewhere in the first few lines
                self::assertStringContainsString(
                    'declare(strict_types=1);',
                    $content,
                    "File {$file->getPathname()} must contain strict types declaration",
                );

                // Verify it's within the first 5 lines
                $lines                 = explode("\n", $content);
                $foundInFirstFiveLines = false;
                for ($i = 0; $i < min(5, count($lines)); ++$i) {
                    if (str_contains($lines[$i], 'declare(strict_types=1)')) {
                        $foundInFirstFiveLines = true;
                        break;
                    }
                }

                self::assertTrue(
                    $foundInFirstFiveLines,
                    "File {$file->getPathname()} must have strict types declaration within first 5 lines",
                );
            }
        }
    }

    /**
     * Test that exception classes have strict types
     *
     * @test
     */
    public function testExceptionClassesHaveStrictTypesDeclaration(): void
    {
        $exceptionPath = __DIR__ . '/../../../../src/Component/Models/src/Exception';

        if (!is_dir($exceptionPath)) {
            self::markTestSkipped('Exception directory does not exist');
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($exceptionPath, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());

                // Check that the file contains declare(strict_types=1) somewhere in the first few lines
                self::assertStringContainsString(
                    'declare(strict_types=1);',
                    $content,
                    "File {$file->getPathname()} must contain strict types declaration",
                );
            }
        }
    }
}

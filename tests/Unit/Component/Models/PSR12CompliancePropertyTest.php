<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Tests\Unit;

use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for PSR-12 compliance in Models component
 *
 * @author FHIR Tools Contributors
 */
class PSR12CompliancePropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Feature: fhir-models-component, Property 5: Code quality standards compliance (PSR-12)
     *
     * @test
     */
    public function testAllGeneratedModelsFollowPSR12Standards(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B']),
            Generator\elements(['Resource', 'DataType']),
        )->withMaxSize(10)->then(function(string $version, string $type) {
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

                    // Test 1: File should start with <?php and have strict types declaration
                    self::assertStringStartsWith(
                        '<?php',
                        $content,
                        "File {$file->getPathname()} should start with <?php",
                    );

                    // Check for strict types declaration in first few lines
                    $lines          = explode("\n", $content);
                    $hasStrictTypes = false;
                    for ($i = 0; $i < min(5, count($lines)); ++$i) {
                        if (str_contains($lines[$i], 'declare(strict_types=1)')) {
                            $hasStrictTypes = true;
                            break;
                        }
                    }
                    self::assertTrue(
                        $hasStrictTypes,
                        "File {$file->getPathname()} should have strict types declaration in first 5 lines",
                    );

                    // Test 2: Should have proper namespace declaration
                    self::assertMatchesRegularExpression(
                        '/namespace\s+Ardenexal\\\\FHIRTools\\\\Component\\\\Models\\\\' . preg_quote($version, '/') . '\\\\' . preg_quote($type, '/') . '/',
                        $content,
                        "File {$file->getPathname()} should have proper namespace declaration",
                    );

                    // Test 3: Should not have trailing whitespace on lines
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNumber => $line) {
                        self::assertStringEndsNotWith(
                            ' ',
                            $line,
                            "File {$file->getPathname()} line " . ($lineNumber + 1) . ' should not have trailing whitespace',
                        );
                        self::assertStringEndsNotWith(
                            "\t",
                            $line,
                            "File {$file->getPathname()} line " . ($lineNumber + 1) . ' should not have trailing tabs',
                        );
                    }

                    // Test 4: Should use 4 spaces for indentation (not tabs) - but allow tabs in string literals
                    $lines = explode("\n", $content);
                    foreach ($lines as $lineNumber => $line) {
                        // Skip lines that are string literals or comments that might legitimately contain tabs
                        if (preg_match('/^\s*\/\*/', $line) || preg_match('/^\s*\*/', $line) || preg_match('/^\s*\/\//', $line)) {
                            continue; // Skip comment lines
                        }
                        if (preg_match('/^\s*case\s+.*=\s*[\'"]/', $line)) {
                            continue; // Skip enum case lines that might have tabs in string values
                        }

                        // Check for tabs used for indentation (at start of line)
                        if (preg_match('/^\t/', $line)) {
                            self::fail("File {$file->getPathname()} line " . ($lineNumber + 1) . ' should use spaces for indentation, not tabs');
                        }
                    }

                    // Test 5: Should have proper line endings (Unix style)
                    self::assertStringNotContainsString(
                        "\r\n",
                        $content,
                        "File {$file->getPathname()} should use Unix line endings (LF), not Windows (CRLF)",
                    );

                    // Test 6: Should end with a single newline
                    self::assertStringEndsWith(
                        "\n",
                        $content,
                        "File {$file->getPathname()} should end with a newline",
                    );

                    // Test 7: Should not have multiple consecutive blank lines
                    self::assertStringNotContainsString(
                        "\n\n\n",
                        $content,
                        "File {$file->getPathname()} should not have more than one consecutive blank line",
                    );
                }
            }
        });
    }

    /**
     * Test that class declarations follow PSR-12 standards
     *
     */
    public function testClassDeclarationsFollowPSR12(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
        )->then(function(string $version, string $type) {
            $basePath = __DIR__ . '/../../../../src/Component/Models/src/' . $version . '/' . $type;

            if (!is_dir($basePath)) {
                return;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            );

            foreach ($iterator as $file) {
                if ($file->getExtension() === 'php') {
                    $content = file_get_contents($file->getPathname());

                    // Test class declaration format
                    if (preg_match('/^(abstract\s+)?class\s+(\w+)/m', $content, $matches)) {
                        $className = $matches[2];

                        // Class name should match file name (without .php extension)
                        $expectedClassName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                        self::assertSame(
                            $expectedClassName,
                            $className,
                            "Class name {$className} should match filename in {$file->getPathname()}",
                        );
                    }

                    // Test enum declaration format (for Enum type)
                    if ($type === 'Enum' && preg_match('/^enum\s+(\w+)/m', $content, $matches)) {
                        $enumName = $matches[1];

                        // Enum name should match file name
                        $expectedEnumName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                        self::assertSame(
                            $expectedEnumName,
                            $enumName,
                            "Enum name {$enumName} should match filename in {$file->getPathname()}",
                        );
                    }
                }
            }
        });
    }
}

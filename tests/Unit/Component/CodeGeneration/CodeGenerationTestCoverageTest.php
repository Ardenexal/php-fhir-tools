<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Property-based test for CodeGeneration component test coverage.
 *
 * **Feature: repository-reorganization, Property 20: CodeGeneration test coverage**
 *
 * Tests that the CodeGeneration component has adequate test coverage
 * and follows testing best practices.
 */
class CodeGenerationTestCoverageTest extends TestCase
{
    use TestTrait;

    /**
     * Test that CodeGeneration component has corresponding test files.
     *
     * **Feature: repository-reorganization, Property 20: CodeGeneration test coverage**
     * **Validates: Requirements 7.4**
     *
     * Property: For any CodeGeneration component class, there should be
     * corresponding test files that provide adequate coverage.
     */
    public function testCodeGenerationComponentHasCorrespondingTestFiles(): void
    {
        $componentClasses = $this->getCodeGenerationComponentClasses();
        
        foreach ($componentClasses as $className) {
            $this->forAll(
                Generator\constant($className)
            )->then(function (string $className): void {
                // Skip abstract classes and interfaces for direct test file requirement
                if (!class_exists($className)) {
                    return;
                }
                
                $reflection = new ReflectionClass($className);
                if ($reflection->isAbstract() || $reflection->isInterface()) {
                    return;
                }
                
                // Generate expected test file path
                $expectedTestFile = $this->getExpectedTestFilePath($className);
                
                // Check if test file exists or if the class is tested by other test files
                $hasDirectTest = file_exists($expectedTestFile);
                $hasIndirectTest = $this->isClassTestedIndirectly($className);
                
                self::assertTrue(
                    $hasDirectTest || $hasIndirectTest,
                    "CodeGeneration component class {$className} should have test coverage. " .
                    "Expected test file: {$expectedTestFile} or indirect testing"
                );
            });
        }
    }

    /**
     * Test that CodeGeneration component test files follow naming conventions.
     *
     * **Feature: repository-reorganization, Property 20: CodeGeneration test coverage**
     * **Validates: Requirements 7.4**
     *
     * Property: For any CodeGeneration component test file, it should follow
     * proper naming conventions and be in the correct directory structure.
     */
    public function testCodeGenerationTestFilesFollowNamingConventions(): void
    {
        $testFiles = $this->getCodeGenerationTestFiles();
        
        foreach ($testFiles as $testFile) {
            $this->forAll(
                Generator\constant($testFile)
            )->then(function (string $testFile): void {
                // Check that test file ends with Test.php
                self::assertStringEndsWith(
                    'Test.php',
                    $testFile,
                    "Test file {$testFile} should end with 'Test.php'"
                );
                
                // Check that test file is in tests directory
                self::assertStringContainsString(
                    'tests/',
                    $testFile,
                    "Test file {$testFile} should be in tests directory"
                );
                
                // Check that test file has proper namespace structure
                $content = file_get_contents($testFile);
                self::assertNotFalse($content, "Should be able to read test file {$testFile}");
                
                // Extract namespace from file
                if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
                    $namespace = $matches[1];
                    self::assertStringStartsWith(
                        'Ardenexal\\FHIRTools\\Tests\\',
                        $namespace,
                        "Test file {$testFile} should have proper test namespace"
                    );
                }
            });
        }
    }

    /**
     * Test that CodeGeneration component test classes extend proper base classes.
     *
     * **Feature: repository-reorganization, Property 20: CodeGeneration test coverage**
     * **Validates: Requirements 7.4**
     *
     * Property: For any CodeGeneration component test class, it should extend
     * PHPUnit TestCase and follow testing best practices.
     */
    public function testCodeGenerationTestClassesExtendProperBaseClasses(): void
    {
        $testFiles = $this->getCodeGenerationTestFiles();
        
        foreach ($testFiles as $testFile) {
            $this->forAll(
                Generator\constant($testFile)
            )->then(function (string $testFile): void {
                $content = file_get_contents($testFile);
                self::assertNotFalse($content, "Should be able to read test file {$testFile}");
                
                // Extract class name from file
                if (preg_match('/class\s+(\w+)\s+extends\s+(\w+)/', $content, $matches)) {
                    $className = $matches[1];
                    $parentClass = $matches[2];
                    
                    // Check that test class extends TestCase
                    self::assertEquals(
                        'TestCase',
                        $parentClass,
                        "Test class {$className} in {$testFile} should extend TestCase"
                    );
                    
                    // Check that class name ends with Test
                    self::assertStringEndsWith(
                        'Test',
                        $className,
                        "Test class {$className} should end with 'Test'"
                    );
                }
                
                // Check for property-based testing trait if it's a property test
                if (str_contains($testFile, 'Property') || str_contains($content, 'Property')) {
                    self::assertStringContainsString(
                        'use TestTrait;',
                        $content,
                        "Property-based test file {$testFile} should use Eris TestTrait"
                    );
                }
            });
        }
    }

    /**
     * Test that CodeGeneration component has adequate test method coverage.
     *
     * **Feature: repository-reorganization, Property 20: CodeGeneration test coverage**
     * **Validates: Requirements 7.4**
     *
     * Property: For any CodeGeneration component test class, it should have
     * adequate test methods covering the main functionality.
     */
    public function testCodeGenerationComponentHasAdequateTestMethodCoverage(): void
    {
        $testFiles = $this->getCodeGenerationTestFiles();
        
        foreach ($testFiles as $testFile) {
            $this->forAll(
                Generator\constant($testFile)
            )->then(function (string $testFile): void {
                $content = file_get_contents($testFile);
                self::assertNotFalse($content, "Should be able to read test file {$testFile}");
                
                // Count test methods
                $testMethodCount = preg_match_all('/public\s+function\s+test\w+\s*\(/', $content);
                
                // Each test file should have at least one test method
                self::assertGreaterThan(
                    0,
                    $testMethodCount,
                    "Test file {$testFile} should have at least one test method"
                );
                
                // Check for proper test method documentation
                $testMethods = [];
                if (preg_match_all('/\/\*\*.*?\*\/\s*public\s+function\s+(test\w+)/s', $content, $matches)) {
                    $testMethods = $matches[1];
                }
                
                self::assertGreaterThan(
                    0,
                    count($testMethods),
                    "Test file {$testFile} should have documented test methods"
                );
                
                // Check that test methods have proper naming
                foreach ($testMethods as $method) {
                    self::assertStringStartsWith(
                        'test',
                        $method,
                        "Test method {$method} in {$testFile} should start with 'test'"
                    );
                }
            });
        }
    }

    /**
     * Test that CodeGeneration component test files have proper assertions.
     *
     * **Feature: repository-reorganization, Property 20: CodeGeneration test coverage**
     * **Validates: Requirements 7.4**
     *
     * Property: For any CodeGeneration component test file, it should contain
     * proper assertions and not just empty test methods.
     */
    public function testCodeGenerationTestFilesHaveProperAssertions(): void
    {
        $testFiles = $this->getCodeGenerationTestFiles();
        
        foreach ($testFiles as $testFile) {
            $this->forAll(
                Generator\constant($testFile)
            )->then(function (string $testFile): void {
                $content = file_get_contents($testFile);
                self::assertNotFalse($content, "Should be able to read test file {$testFile}");
                
                // Count assertions in the file
                $assertionCount = preg_match_all('/self::(assert|assertTrue|assertFalse|assertEquals|assertNotNull|assertArrayHasKey|assertStringContainsString|assertGreaterThan|assertLessThan)/', $content);
                
                // Each test file should have assertions
                self::assertGreaterThan(
                    0,
                    $assertionCount,
                    "Test file {$testFile} should contain assertions"
                );
                
                // Check for proper assertion usage (self:: instead of $this->)
                $improperAssertions = preg_match_all('/\$this->(assert\w+)/', $content);
                self::assertEquals(
                    0,
                    $improperAssertions,
                    "Test file {$testFile} should use self::assert* instead of \$this->assert*"
                );
            });
        }
    }

    /**
     * Get all CodeGeneration component classes
     *
     * @return array<string>
     */
    private function getCodeGenerationComponentClasses(): array
    {
        $classes = [];
        $componentDir = 'src/Component/CodeGeneration/src';
        
        if (!is_dir($componentDir)) {
            return $classes;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($componentDir)
        );
        
        $phpFiles = new RegexIterator($iterator, '/\.php$/');
        
        foreach ($phpFiles as $file) {
            $content = file_get_contents($file->getPathname());
            if ($content === false) {
                continue;
            }
            
            // Extract namespace and class name
            if (preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatches) &&
                preg_match('/(?:class|interface|trait)\s+(\w+)/', $content, $classMatches)) {
                $namespace = $namespaceMatches[1];
                $className = $classMatches[1];
                $classes[] = $namespace . '\\' . $className;
            }
        }
        
        return $classes;
    }

    /**
     * Get expected test file path for a class
     *
     * @param string $className
     * @return string
     */
    private function getExpectedTestFilePath(string $className): string
    {
        // Convert class name to test file path
        $classPath = str_replace('\\', '/', $className);
        $classPath = str_replace('Ardenexal/FHIRTools/Component/CodeGeneration/', '', $classPath);
        
        return "tests/Unit/Component/CodeGeneration/{$classPath}Test.php";
    }

    /**
     * Check if a class is tested indirectly by other test files
     *
     * @param string $className
     * @return bool
     */
    private function isClassTestedIndirectly(string $className): bool
    {
        $testFiles = $this->getCodeGenerationTestFiles();
        $shortClassName = substr($className, strrpos($className, '\\') + 1);
        
        foreach ($testFiles as $testFile) {
            $content = file_get_contents($testFile);
            if ($content === false) {
                continue;
            }
            
            // Check if the class is referenced in the test file
            if (str_contains($content, $className) || str_contains($content, $shortClassName)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get all CodeGeneration component test files
     *
     * @return array<string>
     */
    private function getCodeGenerationTestFiles(): array
    {
        $testFiles = [];
        $testDir = 'tests/Unit/Component/CodeGeneration';
        
        if (!is_dir($testDir)) {
            return $testFiles;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($testDir)
        );
        
        $phpFiles = new RegexIterator($iterator, '/Test\.php$/');
        
        foreach ($phpFiles as $file) {
            $testFiles[] = $file->getPathname();
        }
        
        return $testFiles;
    }
}
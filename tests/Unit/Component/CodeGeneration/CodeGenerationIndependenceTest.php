<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Property-based test for CodeGeneration component independence.
 *
 * **Feature: repository-reorganization, Property 18: CodeGeneration independence**
 *
 * Tests that the CodeGeneration component operates independently without
 * requiring external dependencies beyond its declared minimal set.
 */
class CodeGenerationIndependenceTest extends TestCase
{
    use TestTrait;

    /**
     * Test that CodeGeneration component classes have minimal external dependencies.
     *
     * **Feature: repository-reorganization, Property 18: CodeGeneration independence**
     * **Validates: Requirements 7.2**
     *
     * Property: For any CodeGeneration component class, it should only depend on
     * its declared minimal dependencies and not reference external FHIR tools classes.
     */
    public function testCodeGenerationComponentHasMinimalDependencies(): void
    {
        $this->forAll(
            Generator\elements([
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Context\\BuilderContext',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\GenerationException',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\PackageException',
            ])
        )->then(function (string $className): void {
            // Verify the class exists and can be loaded
            self::assertTrue(
                class_exists($className) || interface_exists($className),
                "CodeGeneration component class {$className} should exist and be loadable"
            );

            $reflection = new ReflectionClass($className);
            
            // Get all dependencies from constructor parameters, method parameters, and use statements
            $dependencies = $this->extractClassDependencies($reflection);
            
            // Define allowed dependencies for CodeGeneration component
            $allowedDependencyPrefixes = [
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\', // Own namespace
                'Nette\\PhpGenerator\\',                              // PHP code generation
                'Symfony\\Component\\String\\',                       // String utilities
                'Symfony\\Component\\Intl\\',                         // Internationalization
                'Symfony\\Component\\Validator\\',                    // Validation
                'Exception',                                          // Base PHP exception
                'Throwable',                                          // Base PHP throwable
                'Stringable',                                         // PHP stringable interface
                'ReflectionClass',                                    // PHP reflection
                'DateTimeInterface',                                  // PHP datetime
                'array',                                              // PHP built-in types
                'string',
                'int',
                'float',
                'bool',
                'mixed',
                'void',
                'null',
            ];

            // Filter out PHP keywords and built-in constructs
            $phpKeywords = ['self', 'parent', 'static', 'true', 'false', 'null', 'callable', 'iterable', 'object'];
            $dependencies = array_filter($dependencies, fn($dep) => !in_array($dep, $phpKeywords, true));

            foreach ($dependencies as $dependency) {
                $isAllowed = false;
                foreach ($allowedDependencyPrefixes as $prefix) {
                    if (str_starts_with($dependency, $prefix)) {
                        $isAllowed = true;
                        break;
                    }
                }

                self::assertTrue(
                    $isAllowed,
                    "CodeGeneration component class {$className} should not depend on external class {$dependency}. " .
                    "Only minimal dependencies are allowed: " . implode(', ', $allowedDependencyPrefixes)
                );
            }
        });
    }

    /**
     * Test that CodeGeneration component exceptions are self-contained.
     *
     * **Feature: repository-reorganization, Property 18: CodeGeneration independence**
     * **Validates: Requirements 7.2**
     *
     * Property: For any CodeGeneration component exception, it should be self-contained
     * and not reference external FHIR tools classes.
     */
    public function testCodeGenerationExceptionsAreSelfContained(): void
    {
        $this->forAll(
            Generator\elements([
                GenerationException::class,
                PackageException::class,
            ])
        )->then(function (string $exceptionClass): void {
            $reflection = new ReflectionClass($exceptionClass);
            
            // Verify exception extends from standard Exception
            self::assertTrue(
                $reflection->isSubclassOf(\Exception::class),
                "CodeGeneration exception {$exceptionClass} should extend standard Exception"
            );

            // Verify exception doesn't depend on external FHIR tools classes
            $dependencies = $this->extractClassDependencies($reflection);
            
            foreach ($dependencies as $dependency) {
                self::assertFalse(
                    str_starts_with($dependency, 'Ardenexal\\FHIRTools\\') && 
                    !str_starts_with($dependency, 'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\'),
                    "CodeGeneration exception {$exceptionClass} should not depend on external FHIR tools class {$dependency}"
                );
            }
        });
    }

    /**
     * Test that CodeGeneration component interfaces are properly isolated.
     *
     * **Feature: repository-reorganization, Property 18: CodeGeneration independence**
     * **Validates: Requirements 7.2**
     *
     * Property: For any CodeGeneration component interface, it should define clear
     * contracts without external dependencies beyond minimal requirements.
     */
    public function testCodeGenerationInterfacesAreIsolated(): void
    {
        $this->forAll(
            Generator\elements([
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Generator\\GeneratorInterface',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Context\\BuilderContextInterface',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Package\\PackageLoaderInterface',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Package\\PackageInterface',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Service\\CodeGenerationServiceInterface',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Service\\GenerationResultInterface',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Configuration\\GenerationConfigurationInterface',
            ])
        )->then(function (string $interfaceName): void {
            self::assertTrue(
                interface_exists($interfaceName),
                "CodeGeneration component interface {$interfaceName} should exist"
            );

            $reflection = new ReflectionClass($interfaceName);
            
            // Verify interface is properly defined
            self::assertTrue(
                $reflection->isInterface(),
                "{$interfaceName} should be an interface"
            );

            // Check that interface methods have proper type hints
            foreach ($reflection->getMethods() as $method) {
                // Verify method parameters use proper type hints
                foreach ($method->getParameters() as $parameter) {
                    $type = $parameter->getType();
                    if ($type !== null) {
                        $typeName = $type instanceof \ReflectionNamedType ? $type->getName() : (string) $type;
                        
                        // Ensure type hints don't reference external FHIR tools classes
                        self::assertFalse(
                            str_starts_with($typeName, 'Ardenexal\\FHIRTools\\') && 
                            !str_starts_with($typeName, 'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\'),
                            "Interface {$interfaceName} method {$method->getName()} parameter {$parameter->getName()} " .
                            "should not reference external FHIR tools class {$typeName}"
                        );
                    }
                }

                // Verify return type doesn't reference external classes
                $returnType = $method->getReturnType();
                if ($returnType !== null) {
                    $returnTypeName = $returnType instanceof \ReflectionNamedType ? $returnType->getName() : (string) $returnType;
                    
                    self::assertFalse(
                        str_starts_with($returnTypeName, 'Ardenexal\\FHIRTools\\') && 
                        !str_starts_with($returnTypeName, 'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\'),
                        "Interface {$interfaceName} method {$method->getName()} return type " .
                        "should not reference external FHIR tools class {$returnTypeName}"
                    );
                }
            }
        });
    }

    /**
     * Extract all class dependencies from reflection
     *
     * @param ReflectionClass $reflection
     * @return array<string>
     */
    private function extractClassDependencies(ReflectionClass $reflection): array
    {
        $dependencies = [];

        // Get constructor dependencies
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                $type = $parameter->getType();
                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    $dependencies[] = $type->getName();
                }
            }
        }

        // Get method parameter dependencies
        foreach ($reflection->getMethods() as $method) {
            foreach ($method->getParameters() as $parameter) {
                $type = $parameter->getType();
                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    $dependencies[] = $type->getName();
                }
            }

            // Get return type dependencies
            $returnType = $method->getReturnType();
            if ($returnType instanceof \ReflectionNamedType && !$returnType->isBuiltin()) {
                $dependencies[] = $returnType->getName();
            }
        }

        // Get property type dependencies
        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $type->getName();
            }
        }

        // Get parent class dependencies
        $parent = $reflection->getParentClass();
        if ($parent !== false) {
            $dependencies[] = $parent->getName();
        }

        // Get interface dependencies
        foreach ($reflection->getInterfaces() as $interface) {
            $dependencies[] = $interface->getName();
        }

        return array_unique($dependencies);
    }
}
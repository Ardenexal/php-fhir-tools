<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for CodeGeneration component dependency minimization.
 *
 * **Feature: repository-reorganization, Property 19: CodeGeneration dependency minimization**
 *
 * Tests that the CodeGeneration component has minimal external dependencies
 * and follows dependency minimization principles.
 */
class CodeGenerationDependencyMinimizationTest extends TestCase
{
    use TestTrait;

    /**
     * Test that CodeGeneration component composer.json has minimal dependencies.
     *
     * **Feature: repository-reorganization, Property 19: CodeGeneration dependency minimization**
     * **Validates: Requirements 7.3**
     *
     * Property: For the CodeGeneration component composer.json, it should only declare
     * essential dependencies and avoid unnecessary external packages.
     */
    public function testCodeGenerationComposerHasMinimalDependencies(): void
    {
        $composerPath = 'src/Component/CodeGeneration/composer.json';

        self::assertFileExists($composerPath, 'CodeGeneration component composer.json should exist');

        $composerContent = file_get_contents($composerPath);
        self::assertNotFalse($composerContent, 'Should be able to read composer.json');

        $composerData = json_decode($composerContent, true);
        self::assertIsArray($composerData, 'composer.json should contain valid JSON');

        // Define maximum allowed dependencies for CodeGeneration component
        // Includes: PHP, ext-zip, nette/php-generator, composer/semver, symfony/string,
        // amphp/http-client, symfony/intl, symfony/validator, symfony/filesystem, symfony/console
        $maxAllowedDependencies = 12;

        $dependencies    = $composerData['require'] ?? [];
        $dependencyCount = count($dependencies);

        self::assertLessThanOrEqual(
            $maxAllowedDependencies,
            $dependencyCount,
            "CodeGeneration component should have minimal dependencies. Found {$dependencyCount}, maximum allowed: {$maxAllowedDependencies}. Dependencies: " . implode(', ', array_keys($dependencies)),
        );

        // Verify essential dependencies are present
        $essentialDependencies = [
            'php'                 => '>=8.2',
            'nette/php-generator' => '^4.1',
            'symfony/string'      => '^6.4|^7.0',
        ];

        foreach ($essentialDependencies as $package => $expectedVersion) {
            self::assertArrayHasKey(
                $package,
                $dependencies,
                "CodeGeneration component should have essential dependency: {$package}",
            );

            self::assertEquals(
                $expectedVersion,
                $dependencies[$package],
                "CodeGeneration component dependency {$package} should have correct version constraint",
            );
        }

        // Verify no forbidden dependencies (heavy ORM/framework packages)
        $forbiddenDependencies = [
            'doctrine/orm',
            'symfony/framework-bundle',
            'guzzlehttp/guzzle',
            'monolog/monolog',
        ];

        foreach ($forbiddenDependencies as $forbiddenPackage) {
            self::assertArrayNotHasKey(
                $forbiddenPackage,
                $dependencies,
                "CodeGeneration component should not depend on heavy package: {$forbiddenPackage}",
            );
        }
    }

    /**
     * Test that CodeGeneration component classes have minimal constructor dependencies.
     *
     * **Feature: repository-reorganization, Property 19: CodeGeneration dependency minimization**
     * **Validates: Requirements 7.3**
     *
     * Property: For any CodeGeneration component class, its constructor should have
     * minimal parameters to reduce coupling and improve testability.
     */
    public function testCodeGenerationClassesHaveMinimalConstructorDependencies(): void
    {
        $this->forAll(
            Generator\elements([
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Context\\BuilderContext',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\GenerationException',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\PackageException',
            ]),
        )->then(function(string $className): void {
            if (!class_exists($className)) {
                self::markTestSkipped("Class {$className} does not exist yet");
            }

            $reflection  = new \ReflectionClass($className);
            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                // No constructor is fine - minimal dependencies
                self::assertTrue(true, "Class {$className} has no constructor - minimal dependencies");

                return;
            }

            $parameterCount       = $constructor->getNumberOfParameters();
            $maxAllowedParameters = 5; // Reasonable limit for constructor parameters

            self::assertLessThanOrEqual(
                $maxAllowedParameters,
                $parameterCount,
                "Class {$className} constructor should have minimal parameters. Found {$parameterCount}, maximum allowed: {$maxAllowedParameters}",
            );

            // Check that parameters are properly typed
            foreach ($constructor->getParameters() as $parameter) {
                $type = $parameter->getType();
                self::assertNotNull(
                    $type,
                    "Constructor parameter {$parameter->getName()} in {$className} should have type hint for better dependency management",
                );
            }
        });
    }

    /**
     * Test that CodeGeneration component interfaces have minimal method signatures.
     *
     * **Feature: repository-reorganization, Property 19: CodeGeneration dependency minimization**
     * **Validates: Requirements 7.3**
     *
     * Property: For any CodeGeneration component interface, its methods should have
     * minimal parameters and focused responsibilities.
     */
    public function testCodeGenerationInterfacesHaveMinimalMethodSignatures(): void
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
            ]),
        )->then(function(string $interfaceName): void {
            if (!interface_exists($interfaceName)) {
                self::markTestSkipped("Interface {$interfaceName} does not exist yet");
            }

            $reflection = new \ReflectionClass($interfaceName);
            $methods    = $reflection->getMethods();

            foreach ($methods as $method) {
                $parameterCount       = $method->getNumberOfParameters();
                $maxAllowedParameters = 4; // Reasonable limit for interface method parameters

                self::assertLessThanOrEqual(
                    $maxAllowedParameters,
                    $parameterCount,
                    "Interface {$interfaceName} method {$method->getName()} should have minimal parameters. Found {$parameterCount}, maximum allowed: {$maxAllowedParameters}",
                );

                // Check that all parameters are properly typed
                foreach ($method->getParameters() as $parameter) {
                    $type = $parameter->getType();
                    self::assertNotNull(
                        $type,
                        "Interface {$interfaceName} method {$method->getName()} parameter {$parameter->getName()} should have type hint",
                    );
                }

                // Check that return type is specified
                $returnType = $method->getReturnType();
                self::assertNotNull(
                    $returnType,
                    "Interface {$interfaceName} method {$method->getName()} should have return type specified",
                );
            }
        });
    }

    /**
     * Test that CodeGeneration component has no circular dependencies.
     *
     * **Feature: repository-reorganization, Property 19: CodeGeneration dependency minimization**
     * **Validates: Requirements 7.3**
     *
     * Property: For any CodeGeneration component class, it should not have circular
     * dependencies within the component or with external classes.
     */
    public function testCodeGenerationComponentHasNoCircularDependencies(): void
    {
        $this->forAll(
            Generator\elements([
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Context\\BuilderContext',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\GenerationException',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\PackageException',
            ]),
        )->then(function(string $className): void {
            if (!class_exists($className)) {
                self::markTestSkipped("Class {$className} does not exist yet");
            }

            $dependencyChain = [];
            $this->checkForCircularDependencies($className, $dependencyChain);

            // If we reach here without exception, no circular dependencies were found
            self::assertTrue(true, "Class {$className} has no circular dependencies");
        });
    }

    /**
     * Test that CodeGeneration component exceptions are lightweight.
     *
     * **Feature: repository-reorganization, Property 19: CodeGeneration dependency minimization**
     * **Validates: Requirements 7.3**
     *
     * Property: For any CodeGeneration component exception, it should be lightweight
     * with minimal dependencies and simple structure.
     */
    public function testCodeGenerationExceptionsAreLightweight(): void
    {
        $this->forAll(
            Generator\elements([
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\GenerationException',
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Exception\\PackageException',
            ]),
        )->then(function(string $exceptionClass): void {
            if (!class_exists($exceptionClass)) {
                self::markTestSkipped("Exception class {$exceptionClass} does not exist yet");
            }

            $reflection = new \ReflectionClass($exceptionClass);

            // Check that exception extends standard Exception
            self::assertTrue(
                $reflection->isSubclassOf(\Exception::class),
                "Exception {$exceptionClass} should extend standard Exception",
            );

            // Check that exception has minimal own properties (excluding inherited ones)
            $ownProperties = array_filter(
                $reflection->getProperties(),
                fn ($property) => $property->getDeclaringClass()->getName() === $exceptionClass,
            );
            $maxAllowedProperties = 2; // context and maybe one more - keep it simple

            self::assertLessThanOrEqual(
                $maxAllowedProperties,
                count($ownProperties),
                "Exception {$exceptionClass} should have minimal own properties. Found " . count($ownProperties) . ", maximum allowed: {$maxAllowedProperties}",
            );

            // Check that exception has minimal methods (beyond inherited ones)
            $ownMethods = array_filter(
                $reflection->getMethods(),
                fn ($method) => $method->getDeclaringClass()->getName() === $exceptionClass,
            );

            $maxAllowedMethods = 16; // Allow for factory methods and getters

            self::assertLessThanOrEqual(
                $maxAllowedMethods,
                count($ownMethods),
                "Exception {$exceptionClass} should have minimal own methods. Found " . count($ownMethods) . ", maximum allowed: {$maxAllowedMethods}",
            );
        });
    }

    /**
     * Recursively check for circular dependencies
     *
     * @param string        $className
     * @param array<string> $dependencyChain
     *
     * @throws \RuntimeException if circular dependency is found
     */
    private function checkForCircularDependencies(string $className, array &$dependencyChain): void
    {
        if (in_array($className, $dependencyChain, true)) {
            throw new \RuntimeException('Circular dependency detected: ' . implode(' -> ', $dependencyChain) . " -> {$className}");
        }

        $dependencyChain[] = $className;

        if (!class_exists($className) && !interface_exists($className)) {
            return;
        }

        $reflection  = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                $type = $parameter->getType();
                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    $dependencyClass = $type->getName();

                    // Only check dependencies within the CodeGeneration component
                    if (str_starts_with($dependencyClass, 'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\')) {
                        $this->checkForCircularDependencies($dependencyClass, $dependencyChain);
                    }
                }
            }
        }

        array_pop($dependencyChain);
    }
}

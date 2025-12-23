<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Exception\ModelsException;
use Ardenexal\FHIRTools\Exception\FHIRToolsException;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ModelsException class.
 *
 * Tests specific examples and edge cases for the ModelsException functionality.
 */
class ModelsExceptionTest extends TestCase
{
    /**
     * Test that ModelsException extends FHIRToolsException.
     */
    public function testModelsExceptionExtendsFHIRToolsException(): void
    {
        $exception = ModelsException::unsupportedVersion('R3');

        self::assertInstanceOf(FHIRToolsException::class, $exception);
        self::assertInstanceOf(\Exception::class, $exception);
    }

    /**
     * Test unsupportedVersion exception creation.
     */
    public function testUnsupportedVersionExceptionCreation(): void
    {
        $version   = 'R3';
        $exception = ModelsException::unsupportedVersion($version);

        self::assertInstanceOf(ModelsException::class, $exception);
        self::assertStringContainsString("Unsupported FHIR version: {$version}", $exception->getMessage());
        self::assertStringContainsString('Supported versions: R4, R4B, R5', $exception->getMessage());
    }

    /**
     * Test modelNotFound exception creation.
     */
    public function testModelNotFoundExceptionCreation(): void
    {
        $className = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRNonExistent';
        $exception = ModelsException::modelNotFound($className);

        self::assertInstanceOf(ModelsException::class, $exception);
        self::assertEquals("FHIR model class not found: {$className}", $exception->getMessage());
    }

    /**
     * Test invalidNamespace exception creation.
     */
    public function testInvalidNamespaceExceptionCreation(): void
    {
        $namespace = 'Invalid\\Namespace\\Pattern';
        $exception = ModelsException::invalidNamespace($namespace);

        self::assertInstanceOf(ModelsException::class, $exception);
        self::assertEquals("Invalid FHIR model namespace: {$namespace}", $exception->getMessage());
    }

    /**
     * Test that all static factory methods return ModelsException instances.
     */
    public function testAllStaticFactoryMethodsReturnModelsException(): void
    {
        $unsupportedVersionException = ModelsException::unsupportedVersion('INVALID');
        $modelNotFoundException      = ModelsException::modelNotFound('NonExistentClass');
        $invalidNamespaceException   = ModelsException::invalidNamespace('Invalid\\Namespace');

        self::assertInstanceOf(ModelsException::class, $unsupportedVersionException);
        self::assertInstanceOf(ModelsException::class, $modelNotFoundException);
        self::assertInstanceOf(ModelsException::class, $invalidNamespaceException);
    }

    /**
     * Test exception messages with various inputs.
     */
    public function testExceptionMessagesWithVariousInputs(): void
    {
        // Test with empty string
        $emptyVersionException = ModelsException::unsupportedVersion('');
        self::assertStringContainsString('Unsupported FHIR version: ', $emptyVersionException->getMessage());

        // Test with special characters
        $specialCharException = ModelsException::modelNotFound('Class\\With\\Special\\Characters\\$#@');
        self::assertStringContainsString('Class\\With\\Special\\Characters\\$#@', $specialCharException->getMessage());

        // Test with very long string
        $longString          = str_repeat('VeryLongClassName', 100);
        $longStringException = ModelsException::invalidNamespace($longString);
        self::assertStringContainsString($longString, $longStringException->getMessage());
    }

    /**
     * Test that exceptions can be thrown and caught properly.
     */
    public function testExceptionsCanBeThrownAndCaught(): void
    {
        // Test unsupportedVersion exception
        try {
            throw ModelsException::unsupportedVersion('R3');
        } catch (ModelsException $e) {
            self::assertStringContainsString('Unsupported FHIR version: R3', $e->getMessage());
        }

        // Test modelNotFound exception
        try {
            throw ModelsException::modelNotFound('NonExistentClass');
        } catch (ModelsException $e) {
            self::assertStringContainsString('FHIR model class not found: NonExistentClass', $e->getMessage());
        }

        // Test invalidNamespace exception
        try {
            throw ModelsException::invalidNamespace('Invalid\\Namespace');
        } catch (ModelsException $e) {
            self::assertStringContainsString('Invalid FHIR model namespace: Invalid\\Namespace', $e->getMessage());
        }
    }

    /**
     * Test that exceptions can be caught as parent types.
     */
    public function testExceptionsCanBeCaughtAsParentTypes(): void
    {
        // Test catching as FHIRToolsException
        try {
            throw ModelsException::unsupportedVersion('R3');
        } catch (FHIRToolsException $e) {
            self::assertInstanceOf(ModelsException::class, $e);
        }

        // Test catching as base Exception
        try {
            throw ModelsException::modelNotFound('NonExistentClass');
        } catch (\Exception $e) {
            self::assertInstanceOf(ModelsException::class, $e);
        }
    }

    /**
     * Test exception inheritance hierarchy.
     */
    public function testExceptionInheritanceHierarchy(): void
    {
        $exception = ModelsException::unsupportedVersion('R3');

        // Test inheritance chain
        self::assertInstanceOf(ModelsException::class, $exception);
        self::assertInstanceOf(FHIRToolsException::class, $exception);
        self::assertInstanceOf(\Exception::class, $exception);
        self::assertInstanceOf(\Throwable::class, $exception);
    }

    /**
     * Test that exception methods are static.
     */
    public function testExceptionMethodsAreStatic(): void
    {
        $reflection = new \ReflectionClass(ModelsException::class);

        $unsupportedVersionMethod = $reflection->getMethod('unsupportedVersion');
        $modelNotFoundMethod      = $reflection->getMethod('modelNotFound');
        $invalidNamespaceMethod   = $reflection->getMethod('invalidNamespace');

        self::assertTrue($unsupportedVersionMethod->isStatic());
        self::assertTrue($modelNotFoundMethod->isStatic());
        self::assertTrue($invalidNamespaceMethod->isStatic());
    }

    /**
     * Test exception with special values.
     */
    public function testExceptionWithSpecialValues(): void
    {
        // Test with numeric values
        $numericException = ModelsException::modelNotFound('123');
        self::assertStringContainsString('FHIR model class not found: 123', $numericException->getMessage());

        // Test with empty string
        $emptyException = ModelsException::invalidNamespace('');
        self::assertStringContainsString('Invalid FHIR model namespace: ', $emptyException->getMessage());
    }

    /**
     * Test that exception messages are properly formatted.
     */
    public function testExceptionMessagesAreProperlyFormatted(): void
    {
        $version   = 'TestVersion';
        $className = 'TestClassName';
        $namespace = 'TestNamespace';

        $unsupportedVersionException = ModelsException::unsupportedVersion($version);
        $modelNotFoundException      = ModelsException::modelNotFound($className);
        $invalidNamespaceException   = ModelsException::invalidNamespace($namespace);

        // Test that messages are properly formatted and contain expected content
        self::assertMatchesRegularExpression(
            '/^Unsupported FHIR version: .+ Supported versions: .+$/',
            $unsupportedVersionException->getMessage(),
        );

        self::assertMatchesRegularExpression(
            '/^FHIR model class not found: .+$/',
            $modelNotFoundException->getMessage(),
        );

        self::assertMatchesRegularExpression(
            '/^Invalid FHIR model namespace: .+$/',
            $invalidNamespaceException->getMessage(),
        );
    }
}

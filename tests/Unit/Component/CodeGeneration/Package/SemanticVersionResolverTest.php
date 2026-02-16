<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\SemanticVersionResolver;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for SemanticVersionResolver
 *
 * @author FHIR Tools
 */
class SemanticVersionResolverTest extends TestCase
{
    private SemanticVersionResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new SemanticVersionResolver();
    }

    /**
     * Test resolveBestVersion with caret constraint
     */
    public function testResolveBestVersionWithCaretConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.1.0', '1.2.0', '2.0.0'];
        $constraint        = '^1.0.0';

        $result = $this->resolver->resolveBestVersion($constraint, $availableVersions);

        self::assertSame('1.2.0', $result);
    }

    /**
     * Test resolveBestVersion with tilde constraint
     */
    public function testResolveBestVersionWithTildeConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.0.1', '1.0.2', '1.1.0'];
        $constraint        = '~1.0.0';

        $result = $this->resolver->resolveBestVersion($constraint, $availableVersions);

        self::assertSame('1.0.2', $result);
    }

    /**
     * Test resolveBestVersion with greater than or equal constraint
     */
    public function testResolveBestVersionWithGreaterThanOrEqualConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.5.0', '2.0.0', '2.5.0', '3.0.0'];
        $constraint        = '>=1.0.0';

        $result = $this->resolver->resolveBestVersion($constraint, $availableVersions);

        self::assertSame('3.0.0', $result);
    }

    /**
     * Test resolveBestVersion throws exception when no versions available
     */
    public function testResolveBestVersionThrowsExceptionWhenNoVersionsAvailable(): void
    {
        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('not found');

        $this->resolver->resolveBestVersion('^1.0.0', []);
    }

    /**
     * Test resolveBestVersion throws exception when no version satisfies constraint
     */
    public function testResolveBestVersionThrowsExceptionWhenNoVersionSatisfiesConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.1.0'];
        $constraint        = '^2.0.0';

        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('not found');

        $this->resolver->resolveBestVersion($constraint, $availableVersions);
    }

    /**
     * Test satisfies returns true for matching version
     */
    public function testSatisfiesReturnsTrueForMatchingVersion(): void
    {
        $result = $this->resolver->satisfies('1.2.0', '^1.0.0');

        self::assertTrue($result);
    }

    /**
     * Test satisfies returns false for non-matching version
     */
    public function testSatisfiesReturnsFalseForNonMatchingVersion(): void
    {
        $result = $this->resolver->satisfies('2.0.0', '^1.0.0');

        self::assertFalse($result);
    }

    /**
     * Test compare versions
     */
    public function testCompareVersions(): void
    {
        self::assertSame(-1, $this->resolver->compare('1.0.0', '1.1.0'));
        self::assertSame(0, $this->resolver->compare('1.0.0', '1.0.0'));
        self::assertSame(1, $this->resolver->compare('1.1.0', '1.0.0'));
    }

    /**
     * Test getLatestVersion
     */
    public function testGetLatestVersion(): void
    {
        $versions = ['1.0.0', '1.2.0', '1.1.0', '2.0.0'];

        $result = $this->resolver->getLatestVersion($versions);

        self::assertSame('2.0.0', $result);
    }

    /**
     * Test getLatestVersion throws exception when no versions provided
     */
    public function testGetLatestVersionThrowsExceptionWhenNoVersionsProvided(): void
    {
        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('not found');

        $this->resolver->getLatestVersion([]);
    }

    /**
     * Test isValidConstraint
     */
    public function testIsValidConstraint(): void
    {
        self::assertTrue($this->resolver->isValidConstraint('^1.0.0'));
        self::assertTrue($this->resolver->isValidConstraint('~1.2.0'));
        self::assertTrue($this->resolver->isValidConstraint('>=1.0.0'));
        self::assertFalse($this->resolver->isValidConstraint('invalid-constraint'));
    }
}

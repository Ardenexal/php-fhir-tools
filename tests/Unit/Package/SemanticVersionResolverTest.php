<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Package;

use Ardenexal\FHIRTools\Component\Package\SemanticVersionResolver;
use Ardenexal\FHIRTools\Exception\PackageException;
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

    public function testResolveBestVersionWithCaretConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.1.0', '1.2.0', '2.0.0'];
        $constraint        = '^1.0.0';

        $result = $this->resolver->resolveBestVersion($constraint, $availableVersions);

        self::assertSame('1.2.0', $result);
    }

    public function testResolveBestVersionWithTildeConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.0.1', '1.0.2', '1.1.0'];
        $constraint        = '~1.0.0';

        $result = $this->resolver->resolveBestVersion($constraint, $availableVersions);

        self::assertSame('1.0.2', $result);
    }

    public function testResolveBestVersionWithRangeConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.5.0', '2.0.0', '2.5.0', '3.0.0'];
        $constraint        = '>=1.0.0 <3.0.0';

        $result = $this->resolver->resolveBestVersion($constraint, $availableVersions);

        self::assertSame('2.5.0', $result);
    }

    public function testResolveBestVersionThrowsExceptionWhenNoVersionsAvailable(): void
    {
        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('No versions available to satisfy constraint');

        $this->resolver->resolveBestVersion('^1.0.0', []);
    }

    public function testResolveBestVersionThrowsExceptionWhenNoVersionSatisfiesConstraint(): void
    {
        $availableVersions = ['1.0.0', '1.1.0'];
        $constraint        = '^2.0.0';

        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('No version satisfies constraint');

        $this->resolver->resolveBestVersion($constraint, $availableVersions);
    }

    public function testSatisfiesReturnsTrueForMatchingVersion(): void
    {
        $result = $this->resolver->satisfies('1.2.0', '^1.0.0');

        self::assertTrue($result);
    }

    public function testSatisfiesReturnsFalseForNonMatchingVersion(): void
    {
        $result = $this->resolver->satisfies('2.0.0', '^1.0.0');

        self::assertFalse($result);
    }

    public function testCompareVersions(): void
    {
        self::assertSame(-1, $this->resolver->compare('1.0.0', '1.1.0'));
        self::assertSame(0, $this->resolver->compare('1.0.0', '1.0.0'));
        self::assertSame(1, $this->resolver->compare('1.1.0', '1.0.0'));
    }

    public function testGetLatestVersion(): void
    {
        $versions = ['1.0.0', '1.2.0', '1.1.0', '2.0.0'];

        $result = $this->resolver->getLatestVersion($versions);

        self::assertSame('2.0.0', $result);
    }

    public function testGetLatestVersionThrowsExceptionWhenNoVersionsProvided(): void
    {
        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('No versions available');

        $this->resolver->getLatestVersion([]);
    }

    public function testIsValidConstraint(): void
    {
        self::assertTrue($this->resolver->isValidConstraint('^1.0.0'));
        self::assertTrue($this->resolver->isValidConstraint('~1.2.0'));
        self::assertTrue($this->resolver->isValidConstraint('>=1.0.0 <2.0.0'));
        self::assertFalse($this->resolver->isValidConstraint('invalid-constraint'));
    }

    public function testParseConstraint(): void
    {
        $result = $this->resolver->parseConstraint('^1.0.0');
        self::assertNotEmpty($result);
        self::assertArrayHasKey('operator', $result[0]);
        self::assertArrayHasKey('version', $result[0]);
    }

    public function testParseConstraintThrowsExceptionForInvalidConstraint(): void
    {
        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('Invalid version constraint');

        $this->resolver->parseConstraint('invalid-constraint');
    }
}

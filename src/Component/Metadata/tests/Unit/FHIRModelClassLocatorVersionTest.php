<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelClassLocator;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKind;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRTypeAncestryProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Pins how the locator treats the version it is handed.
 *
 * A named version scopes the namespace search strictly, so a string naming no release matches
 * nothing and every lookup answers null. Downstream that is indistinguishable from "this type has no
 * ancestors": `FHIRTypeAncestryProvider` returns the empty list, and every non-strict `is` answer
 * built on it silently becomes false. Nothing raised, nothing logged.
 *
 * Spec version numbers make the mistake easy — `4.0.1` and `5.0.0` are how FHIR states its own
 * versions, and this project reads them from packages — so the rejection is explicit rather than
 * left to the caller to notice.
 *
 * @author Ardenexal
 */
final class FHIRModelClassLocatorVersionTest extends TestCase
{
    /**
     * @return iterable<string, array{0: string}>
     */
    public static function unknownVersions(): iterable
    {
        yield 'FHIR R4 spec number'  => ['4.0.1'];
        yield 'FHIR R5 spec number'  => ['5.0.0'];
        yield 'an older release'     => ['STU3'];
        yield 'the empty string'     => [''];
        yield 'a namespace fragment' => ['Models\\R4'];
    }

    /**
     * A version naming no release must raise, not quietly match nothing.
     */
    #[DataProvider('unknownVersions')]
    public function testAnUnknownVersionIsRejectedRatherThanSilentlyMatchingNothing(string $version): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown FHIR version');

        (new FHIRModelClassLocator())->locate('Patient', $version, FHIRStructureKind::Resource);
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function knownVersions(): iterable
    {
        yield 'R4'                 => ['R4', 'R4'];
        yield 'R4B'                => ['R4B', 'R4B'];
        yield 'R5'                 => ['R5', 'R5'];
        yield 'lowercase r4'       => ['r4', 'R4'];
        yield 'mixed-case r4b'     => ['r4B', 'R4B'];
    }

    /**
     * Case is folded, not rejected: PHP resolves namespaces case-insensitively, so `r4` already
     * located the R4 class before this validation existed. It must keep doing so.
     */
    #[DataProvider('knownVersions')]
    public function testAKnownVersionResolvesRegardlessOfCase(string $version, string $expectedRelease): void
    {
        $class = (new FHIRModelClassLocator())->locate('Patient', $version, FHIRStructureKind::Resource);

        self::assertNotNull($class);
        self::assertStringContainsString('\\Models\\' . $expectedRelease . '\\', $class);
    }

    /**
     * Passing no version keeps the documented R4-first fallback rather than raising.
     */
    public function testANullVersionStillSearchesEveryReleaseInOrder(): void
    {
        $class = (new FHIRModelClassLocator())->locate('Patient', null, FHIRStructureKind::Resource);

        self::assertNotNull($class);
        self::assertStringContainsString('\\Models\\R4\\', $class, 'The unscoped search answers R4 first.');
    }

    /**
     * The reason the rejection matters: an unrecognised version reaching ancestry is what turns a
     * true conformance answer into a false one. Asking for it must now fail loudly instead.
     */
    public function testAncestryRejectsAnUnknownVersionInsteadOfAnsweringAnEmptyChain(): void
    {
        $ancestry = new FHIRTypeAncestryProvider();

        self::assertSame(['string', 'Element'], $ancestry->ancestryOf('code', 'R4'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown FHIR version "4.0.1"');
        $ancestry->ancestryOf('code', '4.0.1');
    }
}

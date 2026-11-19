<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Reads the FHIR version ranges that the `version-specific-use` extension sets.
 *
 * The extension limits a definition, or one of an extension's contexts, to FHIR versions from
 * `startFhirVersion` to `endFhirVersion`, both inclusive and both optional. Bounds are compared on
 * major and minor version only, so `4.0` covers 4.0.1 and `4.3` covers 4.3.0. A release is named the
 * way this library names it ('R4', 'R4B', 'R5'); the reference validator's wording names it the same
 * way and adds its version, which {@see versionOf()} gives.
 *
 * @internal
 *
 * @author Ardenexal
 */
final class FHIRVersionRange
{
    /** The published version of each release this library generates models for */
    private const array RELEASE_VERSIONS = ['R4' => '4.0.1', 'R4B' => '4.3.0', 'R5' => '5.0.0'];

    /** Release names by major.minor version, for messages about a bound */
    private const array RELEASE_NAMES = ['1.0' => 'DSTU2', '3.0' => 'STU3', '4.0' => 'R4', '4.3' => 'R4B', '5.0' => 'R5', '6.0' => 'R6'];

    /**
     * The published version of a release, such as '5.0.0' for 'R5'; null for a release not known here.
     */
    public static function versionOf(string $release): ?string
    {
        return self::RELEASE_VERSIONS[$release] ?? null;
    }

    /**
     * The release a version bound names, such as 'R4B' for '4.3'; the bound itself when it names none.
     */
    public static function releaseNameOf(string $version): string
    {
        return self::RELEASE_NAMES[self::majorMinor($version)] ?? $version;
    }

    /**
     * Whether $release is before $start: true means a range starting at $start excludes it.
     *
     * False when either side is unknown or unstated, which never excludes.
     */
    public static function isBefore(string $release, ?string $start): bool
    {
        $version = self::versionOf($release);

        return $version !== null && $start !== null && version_compare(self::majorMinor($version), self::majorMinor($start), '<');
    }

    /**
     * Whether $release is after $end: true means a range ending at $end excludes it.
     */
    public static function isAfter(string $release, ?string $end): bool
    {
        $version = self::versionOf($release);

        return $version !== null && $end !== null && version_compare(self::majorMinor($version), self::majorMinor($end), '>');
    }

    /**
     * Whether a range includes $release. A release this class does not know is always included.
     */
    public static function includes(string $release, ?string $start, ?string $end): bool
    {
        return !self::isBefore($release, $start) && !self::isAfter($release, $end);
    }

    /**
     * The first two parts of a version, such as '4.0' for '4.0.1' and for a bare '4'.
     */
    private static function majorMinor(string $version): string
    {
        return implode('.', array_slice([...explode('.', $version), '0'], 0, 2));
    }
}

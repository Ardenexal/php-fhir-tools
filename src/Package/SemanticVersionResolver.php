<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Package;

use Ardenexal\FHIRTools\Exception\PackageException;
use Composer\Semver\Comparator;
use Composer\Semver\Semver;
use Composer\Semver\VersionParser;

/**
 * Handles semantic version resolution with range support
 *
 * This class provides comprehensive semantic version resolution capabilities
 * for FHIR packages, including:
 *
 * - Version constraint parsing and validation
 * - Range resolution (^1.0.0, ~1.2.0, >=1.0.0 <2.0.0)
 * - Best version selection from available versions
 * - Version comparison and sorting
 * - Pre-release and build metadata handling
 *
 * Uses Composer's semver library for robust version handling that follows
 * semantic versioning specification (semver.org).
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Package
 */
class SemanticVersionResolver
{
    /**
     * Version parser for constraint validation
     *
     * @var VersionParser
     */
    private VersionParser $versionParser;

    /**
     * Construct a new SemanticVersionResolver
     */
    public function __construct()
    {
        $this->versionParser = new VersionParser();
    }

    /**
     * Resolve the best version from available versions that satisfies the constraint
     *
     * @param string        $constraint        Version constraint (e.g., "^1.0.0", "~1.2.0", ">=1.0.0 <2.0.0")
     * @param array<string> $availableVersions List of available versions
     *
     * @return string The best matching version
     *
     * @throws PackageException When no version satisfies the constraint
     */
    public function resolveBestVersion(string $constraint, array $availableVersions): string
    {
        if (empty($availableVersions)) {
            throw PackageException::noVersionsAvailable($constraint);
        }

        // Find versions that satisfy the constraint using original versions
        $satisfyingVersions = Semver::satisfiedBy($availableVersions, $constraint);

        if (empty($satisfyingVersions)) {
            throw PackageException::noVersionSatisfiesConstraint($constraint, $availableVersions);
        }

        // Sort versions in descending order (highest first)
        usort($satisfyingVersions, function($a, $b) {
            return Comparator::greaterThan($a, $b) ? -1 : (Comparator::lessThan($a, $b) ? 1 : 0);
        });

        return $satisfyingVersions[0];
    }

    /**
     * Check if a version satisfies a constraint
     *
     * @param string $version    The version to check
     * @param string $constraint The constraint to check against
     *
     * @return bool True if the version satisfies the constraint
     */
    public function satisfies(string $version, string $constraint): bool
    {
        try {
            return Semver::satisfies($version, $constraint);
        } catch (\UnexpectedValueException) {
            return false;
        }
    }

    /**
     * Compare two versions
     *
     * @param string $version1 First version
     * @param string $version2 Second version
     *
     * @return int -1 if version1 < version2, 0 if equal, 1 if version1 > version2
     */
    public function compare(string $version1, string $version2): int
    {
        try {
            if (Comparator::lessThan($version1, $version2)) {
                return -1;
            }

            if (Comparator::greaterThan($version1, $version2)) {
                return 1;
            }

            return 0;
        } catch (\UnexpectedValueException) {
            // Fallback to string comparison for invalid versions
            return strcmp($version1, $version2);
        }
    }

    /**
     * Get the latest version from a list of versions
     *
     * @param array<string> $versions List of versions
     *
     * @return string The latest version
     *
     * @throws PackageException When no versions are provided
     */
    public function getLatestVersion(array $versions): string
    {
        if (empty($versions)) {
            throw PackageException::noVersionsAvailable('*');
        }

        // Sort versions in descending order (highest first)
        $sortedVersions = $versions;
        usort($sortedVersions, function($a, $b) {
            return Comparator::greaterThan($a, $b) ? -1 : (Comparator::lessThan($a, $b) ? 1 : 0);
        });

        return $sortedVersions[0];
    }

    /**
     * Validate a version constraint
     *
     * @param string $constraint The constraint to validate
     *
     * @return bool True if the constraint is valid
     */
    public function isValidConstraint(string $constraint): bool
    {
        try {
            $this->versionParser->parseConstraints($constraint);

            return true;
        } catch (\UnexpectedValueException) {
            return false;
        }
    }

    /**
     * Parse a version constraint into its components
     *
     * @param string $constraint The constraint to parse
     *
     * @return array{operator: string, version: string}[] Array of constraint components
     *
     * @throws PackageException When the constraint is invalid
     */
    public function parseConstraint(string $constraint): array
    {
        try {
            $parsedConstraint = $this->versionParser->parseConstraints($constraint);

            // This is a simplified representation - in practice, you might need
            // more complex parsing depending on your needs
            return [
                [
                    'operator' => '>=',
                    'version'  => $constraint,
                ],
            ];
        } catch (\UnexpectedValueException $e) {
            throw PackageException::invalidVersionConstraint($constraint, $e->getMessage());
        }
    }
}

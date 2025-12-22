<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;

/**
 * Handles semantic version resolution with range support
 *
 * This class provides basic semantic version resolution capabilities
 * for FHIR packages, including:
 *
 * - Version constraint parsing and validation
 * - Basic range resolution (^1.0.0, ~1.2.0, >=1.0.0)
 * - Best version selection from available versions
 * - Version comparison and sorting
 *
 * This is a simplified implementation focused on minimal dependencies
 * for the CodeGeneration component.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Component\CodeGeneration
 */
class SemanticVersionResolver
{
    /**
     * Resolve the best version from available versions that satisfies the constraint
     *
     * @param string        $constraint        Version constraint (e.g., "^1.0.0", "~1.2.0", ">=1.0.0")
     * @param array<string> $availableVersions List of available versions
     *
     * @return string The best matching version
     *
     * @throws PackageException When no version satisfies the constraint
     */
    public function resolveBestVersion(string $constraint, array $availableVersions): string
    {
        if (empty($availableVersions)) {
            throw PackageException::packageNotFound('unknown', $constraint);
        }

        // Find versions that satisfy the constraint
        $satisfyingVersions = array_filter($availableVersions, fn ($version) => $this->satisfies($version, $constraint));

        if (empty($satisfyingVersions)) {
            throw PackageException::packageNotFound('unknown', $constraint);
        }

        // Sort versions in descending order (highest first)
        usort($satisfyingVersions, fn ($a, $b) => $this->compare($b, $a));

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
        // Handle exact version match
        if ($version === $constraint) {
            return true;
        }

        // Handle caret constraint (^1.2.3)
        if (str_starts_with($constraint, '^')) {
            return $this->satisfiesCaretConstraint($version, substr($constraint, 1));
        }

        // Handle tilde constraint (~1.2.3)
        if (str_starts_with($constraint, '~')) {
            return $this->satisfiesTildeConstraint($version, substr($constraint, 1));
        }

        // Handle greater than or equal (>=1.2.3)
        if (str_starts_with($constraint, '>=')) {
            return $this->compare($version, substr($constraint, 2)) >= 0;
        }

        // Handle greater than (>1.2.3)
        if (str_starts_with($constraint, '>')) {
            return $this->compare($version, substr($constraint, 1)) > 0;
        }

        // Handle less than or equal (<=1.2.3)
        if (str_starts_with($constraint, '<=')) {
            return $this->compare($version, substr($constraint, 2)) <= 0;
        }

        // Handle less than (<1.2.3)
        if (str_starts_with($constraint, '<')) {
            return $this->compare($version, substr($constraint, 1)) < 0;
        }

        // Default to exact match
        return $version === $constraint;
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
        $v1Parts = $this->parseVersion($version1);
        $v2Parts = $this->parseVersion($version2);

        // Compare major, minor, patch
        for ($i = 0; $i < 3; ++$i) {
            $v1Part = $v1Parts[$i] ?? 0;
            $v2Part = $v2Parts[$i] ?? 0;

            if ($v1Part < $v2Part) {
                return -1;
            }
            if ($v1Part > $v2Part) {
                return 1;
            }
        }

        return 0;
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
            throw PackageException::packageNotFound('unknown', '*');
        }

        // Sort versions in descending order (highest first)
        $sortedVersions = $versions;
        usort($sortedVersions, fn ($a, $b) => $this->compare($b, $a));

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
        // Basic validation - check for common constraint patterns
        $patterns = [
            '/^\d+\.\d+\.\d+$/',           // Exact version (1.2.3)
            '/^\^\d+\.\d+\.\d+$/',         // Caret constraint (^1.2.3)
            '/^~\d+\.\d+\.\d+$/',          // Tilde constraint (~1.2.3)
            '/^>=\d+\.\d+\.\d+$/',         // Greater than or equal (>=1.2.3)
            '/^>\d+\.\d+\.\d+$/',          // Greater than (>1.2.3)
            '/^<=\d+\.\d+\.\d+$/',         // Less than or equal (<=1.2.3)
            '/^<\d+\.\d+\.\d+$/',          // Less than (<1.2.3)
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $constraint)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if version satisfies caret constraint (^1.2.3)
     *
     * @param string $version    Version to check
     * @param string $constraint Base version for caret constraint
     *
     * @return bool True if version satisfies constraint
     */
    private function satisfiesCaretConstraint(string $version, string $constraint): bool
    {
        $versionParts    = $this->parseVersion($version);
        $constraintParts = $this->parseVersion($constraint);

        // Major version must match
        if ($versionParts[0] !== $constraintParts[0]) {
            return false;
        }

        // Version must be >= constraint
        return $this->compare($version, $constraint) >= 0;
    }

    /**
     * Check if version satisfies tilde constraint (~1.2.3)
     *
     * @param string $version    Version to check
     * @param string $constraint Base version for tilde constraint
     *
     * @return bool True if version satisfies constraint
     */
    private function satisfiesTildeConstraint(string $version, string $constraint): bool
    {
        $versionParts    = $this->parseVersion($version);
        $constraintParts = $this->parseVersion($constraint);

        // Major and minor versions must match
        if ($versionParts[0] !== $constraintParts[0] || $versionParts[1] !== $constraintParts[1]) {
            return false;
        }

        // Version must be >= constraint
        return $this->compare($version, $constraint) >= 0;
    }

    /**
     * Parse version string into major, minor, patch components
     *
     * @param string $version Version string
     *
     * @return array<int> Array of [major, minor, patch]
     */
    private function parseVersion(string $version): array
    {
        // Remove any pre-release or build metadata
        $cleanedVersion = preg_replace('/[-+].*$/', '', $version);

        // Ensure we have a valid string after regex replacement
        if ($cleanedVersion === null) {
            $cleanedVersion = $version;
        }

        $parts = explode('.', $cleanedVersion);

        return [
            (int) $parts[0],
            (int) ($parts[1] ?? 0),
            (int) ($parts[2] ?? 0),
        ];
    }
}

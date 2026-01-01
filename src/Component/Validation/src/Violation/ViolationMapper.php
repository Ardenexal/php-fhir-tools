<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Violation;

use Ardenexal\FHIRTools\Component\Validation\Model\ValidationIssue;
use Ardenexal\FHIRTools\Component\Validation\Model\ValidationResult;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Maps FHIR validation issues to Symfony ConstraintViolationList.
 *
 * This mapper converts ValidationIssue objects to Symfony ConstraintViolation
 * objects with appropriate machine-readable codes, property paths, and messages.
 *
 * Machine codes follow the pattern: fhir.{category}.{subcategory}
 * - fhir.cardinality.min: Minimum cardinality violation
 * - fhir.cardinality.max: Maximum cardinality violation
 * - fhir.type: Type mismatch
 * - fhir.fhirpath: FHIRPath constraint failure
 * - fhir.binding.required: Required binding violation
 * - fhir.binding.extensible: Extensible binding violation
 * - fhir.slice.closed: Closed slicing violation
 * - fhir.slice.cardinality: Slice cardinality violation
 * - fhir.profile.conflict: Profile conflict detected
 *
 * @author FHIR Tools
 */
class ViolationMapper
{
    // Machine-readable error codes
    public const CODE_CARDINALITY_MIN = 'fhir.cardinality.min';
    public const CODE_CARDINALITY_MAX = 'fhir.cardinality.max';
    public const CODE_TYPE = 'fhir.type';
    public const CODE_FHIRPATH = 'fhir.fhirpath';
    public const CODE_BINDING_REQUIRED = 'fhir.binding.required';
    public const CODE_BINDING_EXTENSIBLE = 'fhir.binding.extensible';
    public const CODE_SLICE_CLOSED = 'fhir.slice.closed';
    public const CODE_SLICE_CARDINALITY = 'fhir.slice.cardinality';
    public const CODE_PROFILE_CONFLICT = 'fhir.profile.conflict';
    public const CODE_MUST_SUPPORT = 'fhir.mustsupport';
    public const CODE_INVARIANT = 'fhir.invariant';

    /**
     * Map ValidationResult to Symfony ConstraintViolationList.
     *
     * @param ValidationResult $result The validation result to convert
     * @param mixed|null $root The root value being validated (optional)
     *
     * @return ConstraintViolationListInterface
     */
    public function map(ValidationResult $result, mixed $root = null): ConstraintViolationListInterface
    {
        $violations = [];

        foreach ($result->getIssues() as $issue) {
            $violations[] = $this->mapIssue($issue, $root);
        }

        return new ConstraintViolationList($violations);
    }

    /**
     * Map a single ValidationIssue to ConstraintViolation.
     *
     * @param ValidationIssue $issue The issue to convert
     * @param mixed|null $root The root value being validated (optional)
     *
     * @return ConstraintViolation
     */
    public function mapIssue(ValidationIssue $issue, mixed $root = null): ConstraintViolation
    {
        $code = $this->deriveCode($issue);
        $propertyPath = $this->normalizePropertyPath($issue->getPath());
        $message = $this->enrichMessage($issue);

        return new ConstraintViolation(
            message: $message,
            messageTemplate: $message,
            parameters: [
                '{{ key }}' => $issue->getKey(),
                '{{ profile }}' => $issue->getProfile(),
                '{{ severity }}' => $issue->getSeverity(),
                '{{ code }}' => $code,
            ],
            root: $root,
            propertyPath: $propertyPath,
            invalidValue: null,
            code: $code
        );
    }

    /**
     * Derive machine-readable code from issue properties.
     *
     * @param ValidationIssue $issue
     *
     * @return string
     */
    private function deriveCode(ValidationIssue $issue): string
    {
        $key = $issue->getKey();
        $message = strtolower($issue->getMessage());

        // Check for slicing violations first (more specific than cardinality)
        if (str_contains($key, 'slice')) {
            if (str_contains($message, 'closed') || str_contains($key, 'closed')) {
                return self::CODE_SLICE_CLOSED;
            }
            if (str_contains($message, 'cardinality') || str_contains($key, 'cardinality')) {
                return self::CODE_SLICE_CARDINALITY;
            }
        }

        // Check for binding violations (before cardinality as "required" is ambiguous)
        if (str_contains($key, 'binding')) {
            if (str_contains($message, 'required') || str_contains($key, 'required')) {
                return self::CODE_BINDING_REQUIRED;
            }
            if (str_contains($message, 'extensible') || str_contains($key, 'extensible')) {
                return self::CODE_BINDING_EXTENSIBLE;
            }
        }

        // Check for cardinality violations
        if (str_contains($key, 'cardinality-max') || str_contains($message, 'maximum') || str_contains($message, 'too many') || str_contains($message, 'exceeded')) {
            return self::CODE_CARDINALITY_MAX;
        }

        if (str_contains($key, 'cardinality-min') || str_contains($key, 'cardinality') || str_contains($message, 'minimum')) {
            return self::CODE_CARDINALITY_MIN;
        }

        // Check for "required" after cardinality check
        if (str_contains($message, 'required') && !str_contains($message, 'binding')) {
            return self::CODE_CARDINALITY_MIN;
        }

        // Check for type violations
        if (str_contains($key, 'type') || str_contains($message, 'type')) {
            return self::CODE_TYPE;
        }

        // Check for profile conflicts
        if (str_contains($key, 'conflict') || str_contains($message, 'conflict')) {
            return self::CODE_PROFILE_CONFLICT;
        }

        // Check for mustSupport
        if (str_contains($key, 'mustsupport') || str_contains($message, 'must support')) {
            return self::CODE_MUST_SUPPORT;
        }

        // Check for FHIRPath or invariant
        if (str_contains($key, 'inv-') || str_contains($key, 'constraint') || !empty($key)) {
            return self::CODE_FHIRPATH;
        }

        // Default to invariant
        return self::CODE_INVARIANT;
    }

    /**
     * Normalize property path to Symfony format.
     *
     * Converts FHIR paths like "Patient.name.family" to array syntax like "name[0].family"
     * when appropriate. Removes resource type prefix if present.
     *
     * @param string $path
     *
     * @return string
     */
    private function normalizePropertyPath(string $path): string
    {
        if ($path === '') {
            return '';
        }

        // Remove resource type prefix (e.g., "Patient." -> "")
        $path = preg_replace('/^[A-Z][a-zA-Z]+\./', '', $path);

        // Path is already normalized if it starts with a lowercase letter
        // or contains array notation
        if (!$path || preg_match('/^\[|^[a-z]/', $path)) {
            return $path;
        }

        return $path;
    }

    /**
     * Enrich message with contextual information.
     *
     * @param ValidationIssue $issue
     *
     * @return string
     */
    private function enrichMessage(ValidationIssue $issue): string
    {
        $message = $issue->getMessage();

        // Add profile context if available and not already in message
        if ($issue->getProfile() && !str_contains($message, $issue->getProfile())) {
            $profileName = $this->extractProfileName($issue->getProfile());
            $message .= sprintf(' (Profile: %s)', $profileName);
        }

        // Add constraint key if available and meaningful
        if ($issue->getKey() && !str_contains($message, $issue->getKey())) {
            // Only add if key is not a generic auto-generated one
            if (!preg_match('/^(inv-\d+|constraint-\d+)$/', $issue->getKey())) {
                $message .= sprintf(' [%s]', $issue->getKey());
            }
        }

        return $message;
    }

    /**
     * Extract readable profile name from URL.
     *
     * @param string $profileUrl
     *
     * @return string
     */
    private function extractProfileName(string $profileUrl): string
    {
        // Extract last segment of URL
        $segments = explode('/', $profileUrl);
        $name = end($segments);

        // Convert camelCase or kebab-case to readable format
        $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);
        $name = str_replace(['-', '_'], ' ', $name);

        return $name;
    }

    /**
     * Create a violation directly from issue properties.
     *
     * Convenience method for creating violations without going through ValidationResult.
     *
     * @param string $severity
     * @param string $path
     * @param string $message
     * @param string $key
     * @param string $profile
     * @param mixed|null $root
     *
     * @return ConstraintViolation
     */
    public function createViolation(
        string $severity,
        string $path,
        string $message,
        string $key = '',
        string $profile = '',
        mixed $root = null
    ): ConstraintViolation {
        $issue = new ValidationIssue($severity, $path, $message, $key, $profile);

        return $this->mapIssue($issue, $root);
    }
}

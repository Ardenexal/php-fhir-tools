<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Model;

/**
 * Represents a validation constraint from a FHIR profile.
 *
 * This class encapsulates a single constraint extracted from a StructureDefinition
 * element definition, including cardinality, type restrictions, FHIRPath expressions,
 * and ValueSet bindings.
 *
 * @author FHIR Tools
 */
class ProfileConstraint
{
    /**
     * @param string      $path        Element path (e.g., "Patient.name")
     * @param string      $key         Unique constraint identifier
     * @param string|null $expression  FHIRPath expression for the constraint
     * @param string|null $human       Human-readable description
     * @param int         $min         Minimum cardinality
     * @param string      $max         Maximum cardinality ("*" for unbounded)
     * @param array       $types       Allowed types for this element
     * @param array|null  $binding     ValueSet binding information
     * @param string      $severity    Constraint severity (error, warning)
     * @param bool        $mustSupport Whether element must be supported
     */
    public function __construct(
        public readonly string $path,
        public readonly string $key,
        public readonly ?string $expression = null,
        public readonly ?string $human = null,
        public readonly int $min = 0,
        public readonly string $max = '*',
        public readonly array $types = [],
        public readonly ?array $binding = null,
        public readonly string $severity = 'error',
        public readonly bool $mustSupport = false
    ) {
    }

    /**
     * Check if this constraint has a cardinality requirement.
     */
    public function hasCardinalityConstraint(): bool
    {
        return $this->min > 0 || $this->max !== '*';
    }

    /**
     * Check if this constraint has a FHIRPath expression.
     */
    public function hasFHIRPathConstraint(): bool
    {
        return $this->expression !== null && $this->expression !== '';
    }

    /**
     * Check if this constraint has a ValueSet binding.
     */
    public function hasBindingConstraint(): bool
    {
        return $this->binding !== null && !empty($this->binding);
    }

    /**
     * Check if this constraint has type restrictions.
     */
    public function hasTypeConstraint(): bool
    {
        return !empty($this->types);
    }

    /**
     * Get the binding strength (required, extensible, preferred, example).
     */
    public function getBindingStrength(): ?string
    {
        return $this->binding['strength'] ?? null;
    }

    /**
     * Get the ValueSet URL for binding validation.
     */
    public function getValueSetUrl(): ?string
    {
        return $this->binding['valueSet'] ?? null;
    }

    /**
     * Check if this is an error-level constraint.
     */
    public function isError(): bool
    {
        return $this->severity === 'error';
    }

    /**
     * Check if this is a warning-level constraint.
     */
    public function isWarning(): bool
    {
        return $this->severity === 'warning';
    }

    /**
     * Get the maximum cardinality as an integer (null if unbounded).
     */
    public function getMaxAsInt(): ?int
    {
        if ($this->max === '*') {
            return null;
        }

        return (int) $this->max;
    }

    /**
     * Convert to array representation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'path'        => $this->path,
            'key'         => $this->key,
            'expression'  => $this->expression,
            'human'       => $this->human,
            'min'         => $this->min,
            'max'         => $this->max,
            'types'       => $this->types,
            'binding'     => $this->binding,
            'severity'    => $this->severity,
            'mustSupport' => $this->mustSupport,
        ];
    }
}

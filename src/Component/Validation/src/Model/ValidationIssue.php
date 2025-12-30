<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Model;

/**
 * Represents a single validation issue found during profile validation.
 *
 * This class encapsulates information about a validation failure or warning,
 * including the element path, severity, and human-readable message.
 *
 * @author FHIR Tools
 */
class ValidationIssue
{
    public const SEVERITY_ERROR = 'error';

    public const SEVERITY_WARNING = 'warning';

    public const SEVERITY_INFO = 'information';

    /**
     * @param string $severity Severity level (error, warning, information)
     * @param string $path     Element path where issue occurred
     * @param string $message  Human-readable description
     * @param string $key      Constraint key that failed
     * @param string $profile  Profile URL that defined the constraint
     */
    public function __construct(
        public readonly string $severity,
        public readonly string $path,
        public readonly string $message,
        public readonly string $key = '',
        public readonly string $profile = ''
    ) {
    }

    /**
     * Create an error-level issue.
     */
    public static function error(string $path, string $message, string $key = '', string $profile = ''): self
    {
        return new self(self::SEVERITY_ERROR, $path, $message, $key, $profile);
    }

    /**
     * Create a warning-level issue.
     */
    public static function warning(string $path, string $message, string $key = '', string $profile = ''): self
    {
        return new self(self::SEVERITY_WARNING, $path, $message, $key, $profile);
    }

    /**
     * Create an info-level issue.
     */
    public static function info(string $path, string $message, string $key = '', string $profile = ''): self
    {
        return new self(self::SEVERITY_INFO, $path, $message, $key, $profile);
    }

    /**
     * Check if this is an error-level issue.
     */
    public function isError(): bool
    {
        return $this->severity === self::SEVERITY_ERROR;
    }

    /**
     * Check if this is a warning-level issue.
     */
    public function isWarning(): bool
    {
        return $this->severity === self::SEVERITY_WARNING;
    }

    /**
     * Check if this is an info-level issue.
     */
    public function isInfo(): bool
    {
        return $this->severity === self::SEVERITY_INFO;
    }

    /**
     * Get severity level.
     */
    public function getSeverity(): string
    {
        return $this->severity;
    }

    /**
     * Get element path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get message.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get constraint key.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get profile URL.
     */
    public function getProfile(): string
    {
        return $this->profile;
    }

    /**
     * Convert to array representation.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'severity' => $this->severity,
            'path'     => $this->path,
            'message'  => $this->message,
            'key'      => $this->key,
            'profile'  => $this->profile,
        ];
    }

    /**
     * Convert to FHIR OperationOutcome issue format.
     *
     * @return array<string, mixed>
     */
    public function toOperationOutcomeIssue(): array
    {
        return [
            'severity'    => $this->severity,
            'code'        => 'invariant',
            'diagnostics' => $this->message,
            'location'    => $this->path   !== '' ? [$this->path] : [],
            'expression'  => $this->path !== '' ? [$this->path] : [],
        ];
    }
}

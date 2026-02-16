<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\Serialization\Exception\ValidationException;

/**
 * Collects and aggregates validation errors with detailed context
 *
 * This class provides a centralized way to collect, categorize, and report
 * validation errors and warnings during FHIR processing. It supports:
 *
 * - Error collection with severity levels and error codes
 * - Warning collection for non-critical issues
 * - Filtering by severity, error code, and element path
 * - Formatted output for debugging and reporting
 * - Integration with exception throwing for fail-fast behavior
 *
 * The ErrorCollector is designed to be used throughout the FHIR processing
 * pipeline to provide comprehensive error reporting and debugging information.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class ErrorCollector
{
    /**
     * Collection of errors with detailed context information
     *
     * Each error contains:
     * - message: Human-readable error description
     * - path: Element path where the error occurred
     * - code: Machine-readable error code for categorization
     * - severity: Error severity level (error, critical, etc.)
     * - context: Additional context data for debugging
     *
     * @var array<array{
     *     message: string,
     *     path: string,
     *     code: string,
     *     severity: string,
     *     context: array<string, mixed>
     * }>
     */
    private array $errors = [];

    /**
     * Collection of warnings with context information
     *
     * Each warning contains:
     * - message: Human-readable warning description
     * - path: Element path where the warning occurred
     * - context: Additional context data for debugging
     *
     * @var array<array{
     *     message: string,
     *     path: string,
     *     context: array<string, mixed>
     * }>
     */
    private array $warnings = [];

    /**
     * Add an error with detailed context
     *
     * Adds a new error to the collection with the specified details.
     * The error will be categorized by code and severity for later filtering.
     *
     * @param string               $message  Human-readable error description
     * @param string               $path     Element path where error occurred (optional)
     * @param string               $code     Machine-readable error code for categorization
     * @param string               $severity Error severity level (error, critical, etc.)
     * @param array<string, mixed> $context  Additional context data for debugging
     *
     * @return self Returns self for method chaining
     */
    public function addError(
        string $message,
        string $path = '',
        string $code = 'VALIDATION_ERROR',
        string $severity = 'error',
        array $context = []
    ): self {
        $this->errors[] = [
            'message'  => $message,
            'path'     => $path,
            'code'     => $code,
            'severity' => $severity,
            'context'  => $context,
        ];

        return $this;
    }

    /**
     * Add a warning with context
     *
     * @param string               $message
     * @param string               $path
     * @param array<string, mixed> $context
     *
     * @return self
     */
    public function addWarning(string $message, string $path = '', array $context = []): self
    {
        $this->warnings[] = [
            'message' => $message,
            'path'    => $path,
            'context' => $context,
        ];

        return $this;
    }

    /**
     * Check if there are any errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Check if there are any warnings
     *
     * @return bool
     */
    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
    }

    /**
     * Get all errors
     *
     * @return array<array{
     *     message: string,
     *     path: string,
     *     code: string,
     *     severity: string,
     *     context: array<string, mixed>
     * }>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get all warnings
     *
     * @return array<array{
     *     message: string,
     *     path: string,
     *     context: array<string, mixed>
     * }>
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Get errors filtered by severity
     *
     * @param string $severity
     *
     * @return array<array{
     *     message: string,
     *     path: string,
     *     code: string,
     *     severity: string,
     *     context: array<string, mixed>
     * }>
     */
    public function getErrorsBySeverity(string $severity): array
    {
        return array_filter($this->errors, fn ($error) => $error['severity'] === $severity);
    }

    /**
     * Get errors filtered by code
     *
     * @param string $code
     *
     * @return array<array{
     *     message: string,
     *     path: string,
     *     code: string,
     *     severity: string,
     *     context: array<string, mixed>
     * }>
     */
    public function getErrorsByCode(string $code): array
    {
        return array_filter($this->errors, fn ($error) => $error['code'] === $code);
    }

    /**
     * Get errors filtered by path pattern
     *
     * @param string $pathPattern
     *
     * @return array<array{
     *     message: string,
     *     path: string,
     *     code: string,
     *     severity: string,
     *     context: array<string, mixed>
     * }>
     */
    public function getErrorsByPath(string $pathPattern): array
    {
        return array_filter($this->errors, fn ($error) => str_contains($error['path'], $pathPattern));
    }

    /**
     * Get total error count
     *
     * @return int
     */
    public function getErrorCount(): int
    {
        return count($this->errors);
    }

    /**
     * Get total warning count
     *
     * @return int
     */
    public function getWarningCount(): int
    {
        return count($this->warnings);
    }

    /**
     * Clear all errors and warnings
     *
     * @return self
     */
    public function clear(): self
    {
        $this->errors   = [];
        $this->warnings = [];

        return $this;
    }

    /**
     * Get a formatted summary of all errors and warnings
     *
     * @return string
     */
    public function getSummary(): string
    {
        $errorCount   = $this->getErrorCount();
        $warningCount = $this->getWarningCount();

        if ($errorCount === 0 && $warningCount === 0) {
            return 'No errors or warnings found.';
        }

        $summary = [];

        if ($errorCount > 0) {
            $summary[] = "{$errorCount} error(s)";
        }

        if ($warningCount > 0) {
            $summary[] = "{$warningCount} warning(s)";
        }

        return 'Found ' . implode(' and ', $summary) . '.';
    }

    /**
     * Get detailed formatted output of all errors and warnings
     *
     * @return string
     */
    public function getDetailedOutput(): string
    {
        $output = [];

        if (!empty($this->errors)) {
            $output[] = 'ERRORS:';
            foreach ($this->errors as $error) {
                $pathInfo = $error['path'] ? " at {$error['path']}" : '';
                $output[] = "  [{$error['code']}] {$error['message']}{$pathInfo}";

                if (!empty($error['context'])) {
                    $output[] = '    Context: ' . json_encode($error['context'], JSON_PRETTY_PRINT);
                }
            }
        }

        if (!empty($this->warnings)) {
            if (!empty($output)) {
                $output[] = '';
            }
            $output[] = 'WARNINGS:';
            foreach ($this->warnings as $warning) {
                $pathInfo = $warning['path'] ? " at {$warning['path']}" : '';
                $output[] = "  {$warning['message']}{$pathInfo}";

                if (!empty($warning['context'])) {
                    $output[] = '    Context: ' . json_encode($warning['context'], JSON_PRETTY_PRINT);
                }
            }
        }

        return implode("\n", $output);
    }

    /**
     * Throw a ValidationException if there are any errors
     *
     * @throws ValidationException
     */
    public function throwIfErrors(): void
    {
        if ($this->hasErrors()) {
            throw ValidationException::multipleValidationErrors($this->errors);
        }
    }
}

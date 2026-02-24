<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Exception;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;

/**
 * Base exception for FHIR serialization errors.
 *
 * This exception is thrown when errors occur during FHIR serialization
 * or deserialization operations.
 *
 * @author Ardenexal
 */
class FHIRSerializationException extends FHIRToolsException
{
    /**
     * @param string                          $message     The exception message
     * @param int                             $code        The exception code
     * @param \Exception|null                 $previous    The previous exception
     * @param string|null                     $elementPath The element path where the error occurred
     * @param array<string, mixed>            $context     Additional context information
     * @param FHIRSerializationDebugInfo|null $debugInfo   Debug information
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Exception $previous = null,
        public readonly ?string $elementPath = null,
        array $context = [],
        public readonly ?FHIRSerializationDebugInfo $debugInfo = null
    ) {
        parent::__construct($message, $code, $previous, $context);
    }

    /**
     * Create an exception for configuration errors.
     *
     * @param array<string, mixed> $context
     */
    public static function configurationError(string $message, array $context = []): self
    {
        return new self(
            message: "Configuration error: {$message}",
            context: $context,
        );
    }

    /**
     * Create an exception for format errors.
     *
     * @param array<string, mixed> $context
     */
    public static function formatError(string $format, string $message, array $context = []): self
    {
        return new self(
            message: "Format error ({$format}): {$message}",
            context: array_merge($context, ['format' => $format]),
        );
    }

    /**
     * Create an exception for validation errors.
     *
     * @param array<string, mixed> $context
     */
    public static function validationError(string $message, ?string $elementPath = null, array $context = []): self
    {
        return new self(
            message: "Validation error: {$message}",
            elementPath: $elementPath,
            context: $context,
        );
    }

    /**
     * Create an exception for unknown element policy violations.
     *
     * @param array<string, mixed> $context
     */
    public static function unknownElementError(string $elementName, string $policy, ?string $elementPath = null, array $context = []): self
    {
        return new self(
            message: "Unknown element '{$elementName}' encountered with policy '{$policy}'",
            elementPath: $elementPath,
            context: array_merge($context, ['element_name' => $elementName, 'policy' => $policy]),
        );
    }

    /**
     * Create an exception for performance optimization errors.
     *
     * @param array<string, mixed> $context
     */
    public static function performanceError(string $message, array $context = []): self
    {
        return new self(
            message: "Performance optimization error: {$message}",
            context: $context,
        );
    }

    /**
     * Get the element path where the error occurred.
     */
    public function getElementPath(): ?string
    {
        return $this->elementPath;
    }

    /**
     * Get debug information if available.
     */
    public function getDebugInfo(): ?FHIRSerializationDebugInfo
    {
        return $this->debugInfo;
    }

    /**
     * Check if debug information is available.
     */
    public function hasDebugInfo(): bool
    {
        return $this->debugInfo !== null;
    }

    /**
     * Get a detailed error message including context and debug info.
     */
    public function getDetailedMessage(): string
    {
        $message = $this->getMessage();

        if ($this->elementPath !== null) {
            $message .= " (at path: {$this->elementPath})";
        }

        if (!empty($this->context)) {
            $contextStr = json_encode($this->context, JSON_THROW_ON_ERROR);
            $message .= " (context: {$contextStr})";
        }

        if ($this->debugInfo !== null) {
            $message .= " (debug: {$this->debugInfo->getSummary()})";
        }

        return $message;
    }
}

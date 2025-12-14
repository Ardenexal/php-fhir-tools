<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Serialization;

/**
 * Debug information container for FHIR serialization operations.
 *
 * This class provides detailed context and debugging information
 * for FHIR serialization and deserialization operations.
 *
 * @author Kiro AI Assistant
 */
class FHIRSerializationDebugInfo
{
    /**
     * @param string               $operation      The serialization operation (normalize/denormalize)
     * @param string               $format         The format being processed (json/xml)
     * @param string|null          $elementPath    The current element path being processed
     * @param string|null          $objectType     The type of object being processed
     * @param string|null          $normalizerType The type of normalizer being used
     * @param array<string, mixed> $context        The serialization context
     * @param array<string, mixed> $metadata       Additional metadata about the operation
     * @param array<string>        $warnings       Non-fatal warnings encountered
     * @param float|null           $startTime      Operation start time (microtime)
     * @param float|null           $endTime        Operation end time (microtime)
     */
    public function __construct(
        public readonly string $operation,
        public readonly string $format,
        public readonly ?string $elementPath = null,
        public readonly ?string $objectType = null,
        public readonly ?string $normalizerType = null,
        public readonly array $context = [],
        public readonly array $metadata = [],
        public readonly array $warnings = [],
        public readonly ?float $startTime = null,
        public readonly ?float $endTime = null
    ) {
    }

    /**
     * Create debug info for a normalization operation.
     *
     * @param array<string, mixed> $context
     */
    public static function forNormalization(
        string $format,
        ?string $elementPath = null,
        ?string $objectType = null,
        ?string $normalizerType = null,
        array $context = []
    ): self {
        return new self(
            operation: 'normalize',
            format: $format,
            elementPath: $elementPath,
            objectType: $objectType,
            normalizerType: $normalizerType,
            context: $context,
            startTime: microtime(true),
        );
    }

    /**
     * Create debug info for a denormalization operation.
     *
     * @param array<string, mixed> $context
     */
    public static function forDenormalization(
        string $format,
        ?string $elementPath = null,
        ?string $objectType = null,
        ?string $normalizerType = null,
        array $context = []
    ): self {
        return new self(
            operation: 'denormalize',
            format: $format,
            elementPath: $elementPath,
            objectType: $objectType,
            normalizerType: $normalizerType,
            context: $context,
            startTime: microtime(true),
        );
    }

    /**
     * Add a warning to the debug info.
     */
    public function withWarning(string $warning): self
    {
        return new self(
            operation: $this->operation,
            format: $this->format,
            elementPath: $this->elementPath,
            objectType: $this->objectType,
            normalizerType: $this->normalizerType,
            context: $this->context,
            metadata: $this->metadata,
            warnings: array_merge($this->warnings, [$warning]),
            startTime: $this->startTime,
            endTime: $this->endTime,
        );
    }

    /**
     * Add metadata to the debug info.
     *
     * @param array<string, mixed> $metadata
     */
    public function withMetadata(array $metadata): self
    {
        return new self(
            operation: $this->operation,
            format: $this->format,
            elementPath: $this->elementPath,
            objectType: $this->objectType,
            normalizerType: $this->normalizerType,
            context: $this->context,
            metadata: array_merge($this->metadata, $metadata),
            warnings: $this->warnings,
            startTime: $this->startTime,
            endTime: $this->endTime,
        );
    }

    /**
     * Mark the operation as completed.
     */
    public function completed(): self
    {
        return new self(
            operation: $this->operation,
            format: $this->format,
            elementPath: $this->elementPath,
            objectType: $this->objectType,
            normalizerType: $this->normalizerType,
            context: $this->context,
            metadata: $this->metadata,
            warnings: $this->warnings,
            startTime: $this->startTime,
            endTime: microtime(true),
        );
    }

    /**
     * Get the operation duration in milliseconds.
     */
    public function getDurationMs(): ?float
    {
        if ($this->startTime === null || $this->endTime === null) {
            return null;
        }

        return ($this->endTime - $this->startTime) * 1000;
    }

    /**
     * Check if the operation has warnings.
     */
    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
    }

    /**
     * Get the number of warnings.
     */
    public function getWarningCount(): int
    {
        return count($this->warnings);
    }

    /**
     * Convert to array for logging or debugging.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'operation'       => $this->operation,
            'format'          => $this->format,
            'element_path'    => $this->elementPath,
            'object_type'     => $this->objectType,
            'normalizer_type' => $this->normalizerType,
            'context'         => $this->context,
            'metadata'        => $this->metadata,
            'warnings'        => $this->warnings,
            'start_time'      => $this->startTime,
            'end_time'        => $this->endTime,
            'duration_ms'     => $this->getDurationMs(),
            'warning_count'   => $this->getWarningCount(),
        ];
    }

    /**
     * Convert to JSON string for logging.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    /**
     * Create a summary string for quick debugging.
     */
    public function getSummary(): string
    {
        $parts = [
            $this->operation,
            $this->format,
        ];

        if ($this->objectType !== null) {
            $parts[] = $this->objectType;
        }

        if ($this->elementPath !== null) {
            $parts[] = "path:{$this->elementPath}";
        }

        if ($this->getDurationMs() !== null) {
            $parts[] = sprintf('%.2fms', $this->getDurationMs());
        }

        if ($this->hasWarnings()) {
            $parts[] = sprintf('%d warnings', $this->getWarningCount());
        }

        return implode(' | ', $parts);
    }

    /**
     * Get the debug information as an array.
     *
     * @return array<string, mixed>
     */
    public function getDebugInfo(): array
    {
        return $this->toArray();
    }
}

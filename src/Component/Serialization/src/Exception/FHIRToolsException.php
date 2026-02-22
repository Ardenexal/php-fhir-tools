<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Exception;


/**
 * Base exception class for all FHIR Tools related errors
 *
 * This abstract class provides a foundation for all FHIR Tools exceptions,
 * offering enhanced error context management and formatted error reporting.
 * All specific exception types should extend this class to maintain consistency
 * in error handling throughout the application.
 *
 * Features:
 * - Context information storage for debugging
 * - Formatted error messages with context
 * - Fluent interface for adding context data
 * - Consistent error structure across all FHIR Tools exceptions
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
abstract class FHIRToolsException extends \Exception
{
    /**
     * Additional context information about the error
     *
     * This array stores key-value pairs that provide additional context
     * about the error, such as element paths, resource URLs, or other
     * relevant debugging information.
     *
     * @var array<string, mixed>
     */
    protected array $context = [];

    /**
     * Construct a new FHIR Tools exception with optional context
     *
     * @param string               $message  The exception message
     * @param int                  $code     The exception code
     * @param \Exception|null      $previous The previous exception for chaining
     * @param array<string, mixed> $context  Additional context information
     */
    public function __construct(string $message = '', int $code = 0, ?\Exception $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get additional context information about the error
     *
     * Returns the complete context array containing all additional
     * information that was provided when the exception was created
     * or added later via addContext().
     *
     * @return array<string, mixed> The context information
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Add context information to the exception
     *
     * This method allows adding additional context information after
     * the exception has been created. It uses a fluent interface
     * pattern for easy chaining of multiple context additions.
     *
     * @param string $key   The context key
     * @param mixed  $value The context value
     *
     * @return static Returns self for method chaining
     */
    public function addContext(string $key, mixed $value): static
    {
        $this->context[$key] = $value;

        return $this;
    }

    /**
     * Get a formatted error message with context
     *
     * Returns the exception message along with a formatted representation
     * of the context information. This is useful for logging and debugging
     * as it provides all available information in a readable format.
     *
     * @return string The formatted message with context
     */
    public function getFormattedMessage(): string
    {
        $message = $this->getMessage();

        if (!empty($this->context)) {
            $contextString = json_encode($this->context, JSON_PRETTY_PRINT);
            $message .= "\nContext: " . $contextString;
        }

        return $message;
    }
}

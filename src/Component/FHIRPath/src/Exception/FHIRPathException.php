<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Exception;

use RuntimeException;

/**
 * Base exception for all FHIRPath-related errors.
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathException extends RuntimeException
{
    protected int $line = 0;
    protected int $column = 0;
    protected string $expressionContext = '';
    protected ?string $suggestion = null;

    /**
     * Create a FHIRPath exception.
     *
     * @param string $message The error message
     * @param int $line The line number where the error occurred
     * @param int $column The column number where the error occurred
     * @param string $expressionContext The expression context around the error
     * @param string|null $suggestion Optional suggestion for fixing the error
     */
    public function __construct(
        string $message,
        int $line = 0,
        int $column = 0,
        string $expressionContext = '',
        ?string $suggestion = null
    ) {
        parent::__construct($message);
        $this->line = $line;
        $this->column = $column;
        $this->expressionContext = $expressionContext;
        $this->suggestion = $suggestion;
    }

    /**
     * Get the line number where the error occurred.
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * Get the column number where the error occurred.
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * Get the expression context around the error.
     */
    public function getExpressionContext(): string
    {
        return $this->expressionContext;
    }

    /**
     * Get a suggestion for fixing the error.
     */
    public function getSuggestion(): ?string
    {
        return $this->suggestion;
    }

    /**
     * Get the full error message including position and context.
     */
    public function getFullMessage(): string
    {
        $message = $this->getMessage();

        if ($this->line > 0) {
            $message .= sprintf(' at line %d, column %d', $this->line, $this->column);
        }

        if ($this->expressionContext !== '') {
            $message .= "\nContext: " . $this->expressionContext;
        }

        if ($this->suggestion !== null) {
            $message .= "\nSuggestion: " . $this->suggestion;
        }

        return $message;
    }

    /**
     * Get position information as an array.
     *
     * @return array{line: int, column: int}
     */
    public function getPosition(): array
    {
        return [
            'line' => $this->line,
            'column' => $this->column,
        ];
    }
}

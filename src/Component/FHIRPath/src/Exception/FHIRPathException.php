<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Exception;

/**
 * Base exception for all FHIRPath-related errors.
 *
 * @author FHIR Tools Contributors
 */
class FHIRPathException extends \RuntimeException
{
    protected int $expressionLine = 0;

    protected int $expressionColumn = 0;

    protected string $expressionContext = '';

    protected ?string $suggestion = null;

    /**
     * Create a FHIRPath exception.
     *
     * @param string      $message           The error message
     * @param int         $line              The line number where the error occurred in the expression
     * @param int         $column            The column number where the error occurred in the expression
     * @param string      $expressionContext The expression context around the error
     * @param string|null $suggestion        Optional suggestion for fixing the error
     */
    public function __construct(
        string $message,
        int $line = 0,
        int $column = 0,
        string $expressionContext = '',
        ?string $suggestion = null
    ) {
        parent::__construct($message);
        $this->expressionLine    = $line;
        $this->expressionColumn  = $column;
        $this->expressionContext = $expressionContext;
        $this->suggestion        = $suggestion;
    }

    /**
     * Get the line number where the error occurred in the FHIRPath expression.
     */
    public function getExpressionLine(): int
    {
        return $this->expressionLine;
    }

    /**
     * Get the column number where the error occurred in the FHIRPath expression.
     */
    public function getExpressionColumn(): int
    {
        return $this->expressionColumn;
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

        if ($this->expressionLine > 0) {
            $message .= sprintf(' at line %d, column %d', $this->expressionLine, $this->expressionColumn);
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
            'line'   => $this->expressionLine,
            'column' => $this->expressionColumn,
        ];
    }
}

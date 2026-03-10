<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath escape() function.
 *
 * Escapes special characters in the input string using the specified format.
 * Supports: html, json
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class EscapeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('escape');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $string = $input->first();
        if (!is_string($string)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'input', 'string');
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $format = $evaluator->evaluate($parameters[0], $context)->first();
        if (!is_string($format)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'format', 'string');
        }

        $escaped = match ($format) {
            'html'  => htmlspecialchars($string, ENT_QUOTES | ENT_XML1, 'UTF-8'),
            'json'  => $this->escapeJson($string),
            default => throw EvaluationException::invalidFunctionParameter($this->getName(), 'format', "one of: 'html', 'json' (got: '{$format}')"),
        };

        return Collection::single($escaped);
    }

    /**
     * Escape a string for JSON (without surrounding quotes).
     */
    private function escapeJson(string $string): string
    {
        // Use json_encode then strip the surrounding quotes
        $encoded = json_encode($string, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($encoded === false) {
            throw new EvaluationException('Failed to escape string for JSON');
        }

        // Remove surrounding quotes that json_encode adds
        return substr($encoded, 1, -1);
    }
}

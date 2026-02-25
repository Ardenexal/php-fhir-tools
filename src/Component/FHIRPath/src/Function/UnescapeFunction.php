<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath unescape() function.
 *
 * Unescapes special characters in the input string using the specified format.
 * Supports: html, json
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class UnescapeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('unescape');
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

        $unescaped = match ($format) {
            'html'  => html_entity_decode($string, ENT_QUOTES | ENT_XML1, 'UTF-8'),
            'json'  => $this->unescapeJson($string),
            default => throw EvaluationException::invalidFunctionParameter($this->getName(), 'format', "one of: 'html', 'json' (got: '{$format}')"),
        };

        return Collection::single($unescaped);
    }

    /**
     * Unescape a JSON-escaped string (without surrounding quotes).
     */
    private function unescapeJson(string $string): string
    {
        // Wrap in quotes, decode, and unwrap
        $decoded = json_decode('"' . $string . '"');
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new EvaluationException('Failed to unescape string from JSON: ' . json_last_error_msg());
        }

        return $decoded;
    }
}

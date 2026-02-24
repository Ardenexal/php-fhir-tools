<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * replaceMatches(regex: String, substitution: String) : String
 *
 * Returns the input string with all substrings matching the regex replaced by
 * the substitution string. Backreferences use $N notation (e.g. $1 for the
 * first capture group), which is compatible with PHP's preg_replace() syntax.
 *
 * Mirrors the pattern-wrapping logic of matches() — adds PCRE delimiters and
 * the DOTALL ('s') flag if not already present.
 *
 * Spec reference: FHIRPath §5.6
 *
 * @author FHIR Tools Contributors
 */
final class ReplaceMatchesFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('replaceMatches');
    }

    /**
     * Execute the replaceMatches function on the input string.
     *
     * Replaces all occurrences matching the regex pattern with the substitution string,
     * supporting backreferences using $N notation.
     *
     * @param Collection        $input      The input collection (expects single string item)
     * @param array<int, mixed> $parameters [0] = regex pattern expression, [1] = substitution string expression
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with the modified string, or empty if input is empty
     *
     * @throws EvaluationException If input is not a string or regex is invalid
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects exactly 2 parameters (regex pattern and substitution string)
        $this->validateParameterCount($parameters, 2);

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

        $patternResult = $evaluator->evaluateWithContext($parameters[0], $context);
        if ($patternResult->isEmpty()) {
            return Collection::empty();
        }

        $pattern = $patternResult->first();
        if (!is_string($pattern)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'regex', 'string');
        }

        $substitutionResult = $evaluator->evaluateWithContext($parameters[1], $context);
        if ($substitutionResult->isEmpty()) {
            return Collection::empty();
        }

        $substitution = $substitutionResult->first();
        if (!is_string($substitution)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'substitution', 'string');
        }

        // Add PCRE delimiters and DOTALL flag if not already present — same logic as MatchesFunction
        if (!str_starts_with($pattern, '/')) {
            // 's' flag enables DOTALL mode (makes '.' match newlines too)
            $pattern = '/' . $pattern . '/s';
        } else {
            // Pattern already has delimiters, check if it has the DOTALL flag
            $lastSlash = strrpos($pattern, '/');
            $flags     = $lastSlash !== false ? substr($pattern, $lastSlash + 1) : '';
            if (!str_contains($flags, 's')) {
                $pattern .= 's';  // Add DOTALL flag
            }
        }

        // @ suppresses warnings, we check for null to detect errors
        $result = @preg_replace($pattern, $substitution, $string);

        if ($result === null) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'regex', 'valid regular expression');
        }

        return Collection::single($result);
    }
}

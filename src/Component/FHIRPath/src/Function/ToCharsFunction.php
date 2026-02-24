<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * toChars() : collection
 *
 * Returns the list of characters in the input string as a collection. Each
 * element is a single-character string. If the input collection is empty,
 * returns empty. If the string itself is empty, returns empty.
 *
 * Spec reference: FHIRPath §5.6
 *
 * @author FHIR Tools Contributors
 */
final class ToCharsFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('toChars');
    }

    /**
     * Execute the toChars function to split a string into characters.
     *
     * Splits the input string into a collection of individual characters (Unicode-safe).
     *
     * @param Collection        $input      The input collection (expects single string item)
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Collection of single-character strings, or empty if input is empty/not a string
     *
     * @throws EvaluationException If input is not a string
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for toChars)
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $string = $input->first();
        if (!is_string($string)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'input', 'string');
        }

        // Empty string has no characters, return empty collection
        if ($string === '') {
            return Collection::empty();
        }

        // mb_str_split handles multi-byte characters correctly (Unicode-safe)
        // This ensures emoji and accented characters are treated as single characters
        $chars = mb_str_split($string);

        return Collection::from($chars);
    }
}

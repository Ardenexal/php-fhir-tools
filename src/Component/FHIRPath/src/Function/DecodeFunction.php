<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath decode() function.
 *
 * Decodes the input string using the specified decoding format.
 * Supports: base64, hex, urlbase64
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class DecodeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('decode');
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

        $decoded = match ($format) {
            'base64'    => base64_decode($string, true),
            'hex'       => hex2bin($string),
            'urlbase64' => base64_decode(strtr($string, '-_', '+/'), true),
            default     => throw EvaluationException::invalidFunctionParameter($this->getName(), 'format', "one of: 'base64', 'hex', 'urlbase64' (got: '{$format}')"),
        };

        if ($decoded === false) {
            throw new EvaluationException("Failed to decode string using format '{$format}'");
        }

        return Collection::single($decoded);
    }
}

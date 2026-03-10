<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath encode() function.
 *
 * Encodes the input string using the specified encoding format.
 * Supports: base64, hex, urlbase64
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class EncodeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('encode');
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

        $encoded = match ($format) {
            'base64'    => base64_encode($string),
            'hex'       => bin2hex($string),
            'urlbase64' => strtr(base64_encode($string), '+/', '-_'),
            default     => throw EvaluationException::invalidFunctionParameter($this->getName(), 'format', "one of: 'base64', 'hex', 'urlbase64' (got: '{$format}')"),
        };

        return Collection::single($encoded);
    }
}

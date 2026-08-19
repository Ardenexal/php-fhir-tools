<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * FHIRPath hasValue() function.
 *
 * Returns a single boolean: true when the input is exactly one FHIR primitive carrying a primitive
 * value (as opposed to carrying only extensions), false otherwise.
 *
 * This is one of the few FHIRPath functions that is *not* empty-propagating. The FHIR spec defines it
 * as "returns true if the input collection contains a single value which is a FHIR primitive, and it
 * has a primitive value", and the HL7 Java reference engine implements exactly that — `funcHasValue()`
 * answers `false` whenever the focus is not a single item, empty included. Returning empty here instead
 * made every core invariant of the shape `… implies (x.hasValue())` unfalsifiable, because
 * `true implies {}` is `{}` and an empty invariant result is a pass: `bdl-10`
 * (`type = 'document' implies (timestamp.hasValue())`) could not fire on a document with no timestamp.
 *
 * Note the asymmetry with FHIRPathInvariantValidator's deliberate empty-is-pass rule: that rule is about
 * how an invariant's *result* is interpreted and stays untouched. This is about hasValue() reporting
 * honestly on its own input, so `implies` never sees an empty operand it has to guess about.
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class HasValueFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('hasValue');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        // Not a single item — empty, or a repeating element — is not "a single value which is a FHIR
        // primitive", so the answer is a definite false rather than empty or one boolean per item.
        if ($input->count() !== 1) {
            return Collection::single(false);
        }

        $item = $input->first();

        // Check if object has a value property (FHIR primitive pattern).
        // isset(), not property_exists() + a read: on a generated model `value` is a typed
        // property with no default, so an instance carrying no value leaves it *uninitialized*.
        // property_exists() is true for it, and reading it then raises
        // "must not be accessed before initialization" rather than returning null. isset() is
        // exactly the question being asked — declared, initialized, and not null.
        if (is_object($item)) {
            return Collection::single(isset($item->value));
        }

        // For non-objects, any non-null value is considered having value.
        return Collection::single($item !== null);
    }
}

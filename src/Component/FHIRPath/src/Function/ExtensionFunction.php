<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * extension(url : String) : collection
 *
 * FHIR R4 FHIRPath extension function.
 *
 * Shortcut for `.extension.where(url = 'url')`. For each item in the input
 * collection, navigates to its `extension` property and returns only those
 * extension items whose `url` property exactly matches the given URL string.
 *
 * Works on any FHIR element that carries extensions:
 *  - Plain PHP arrays  (from json_decode with associative=true)
 *  - Objects with a public $extension property
 *  - Objects with a getExtension() method
 *
 * Extension URL matching is exact (case-sensitive) per FHIR spec.
 *
 * Does NOT depend on Models or CodeGeneration — uses duck-typed property access only.
 *
 * Spec reference: FHIR R4 FHIRPath supplement §2.1
 *
 * @author FHIR Tools Contributors
 */
final class ExtensionFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('extension');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context', 0, 0);
        }

        // Evaluate the URL parameter expression
        $urlResult = $evaluator->evaluateWithContext($parameters[0], $context);
        if ($urlResult->isEmpty()) {
            return Collection::empty();
        }

        $url = $urlResult->first();
        if (!is_string($url)) {
            throw EvaluationException::invalidFunctionParameter('extension', 'url', 'string');
        }

        // For each input item, navigate to its extension list and filter by URL
        $matched = [];
        foreach ($input as $item) {
            foreach ($this->getExtensions($item) as $ext) {
                $extUrl = $this->getPropertyValue($ext, 'url');
                if ($extUrl === $url) {
                    $matched[] = $ext;
                }
            }
        }

        return Collection::from($matched);
    }

    /**
     * Get the extension list from a FHIR element.
     *
     * Returns an array of extension items (associative arrays or objects).
     * Returns an empty array if the element has no extension property.
     *
     * @return array<int, mixed>
     */
    private function getExtensions(mixed $item): array
    {
        $value = $this->getPropertyValue($item, 'extension');

        if ($value === null) {
            return [];
        }

        // List array → each element is an extension
        if (is_array($value) && array_is_list($value)) {
            return $value;
        }

        // Single extension item (associative array or object)
        return [$value];
    }

    /**
     * Navigate a named property on a FHIR element using duck-typed access.
     *
     * Resolution order:
     *  1. Associative array key
     *  2. Public object property
     *  3. Getter method (get<Property>())
     *
     * Returns null when the property is not found.
     */
    private function getPropertyValue(mixed $item, string $property): mixed
    {
        if (is_array($item)) {
            return array_key_exists($property, $item) ? $item[$property] : null;
        }

        if (is_object($item)) {
            // get_object_vars() called from outside the object returns only public properties,
            // which avoids a dead-catch from accessing private/protected properties directly.
            $vars = get_object_vars($item);
            if (array_key_exists($property, $vars)) {
                return $vars[$property];
            }

            $getter = 'get' . ucfirst($property);
            if (method_exists($item, $getter)) {
                return $item->$getter();
            }
        }

        return null;
    }
}

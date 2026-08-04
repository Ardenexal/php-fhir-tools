<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\HttpClient\XFhirQuery;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

/**
 * Resolves an `application/x-fhir-query` template into a concrete FHIR search string.
 *
 * An x-fhir-query template is a FHIR search string with embedded FHIRPath expressions delimited by
 * double curly braces (Liquid-style), e.g.
 *   `Observation?code=http://loinc.org|65972-2&date=gt{{today()-7 days}}&subject={{%patient.id}}`.
 * Each `{{ … }}` is evaluated against the supplied {@see EvaluationContext} (launch-context resources
 * bound as `%`-prefixed external constants) and substituted per the R5 substitution rules:
 * https://hl7.org/fhir/R5/fhir-xquery.html
 *
 * This resolver is **pure and offline** — it performs no network I/O. It only produces the resolved
 * search string; executing that search against a FHIR server is a separate concern (a FHIR HTTP client).
 *
 * ## Substitution rules implemented
 * - Scalar result → its string form, percent-encoded.
 * - Coding / Identifier → token form `system|code` (or `system|value` for Identifier).
 * - CodeableConcept → each of its `.coding` as a token, comma-joined.
 * - Quantity → `value|system|code`.
 * - Multiple values (a list result) → comma-joined (FHIR "or" semantics).
 * - Temporal results (FHIRPath Date/DateTime/Time) → their ISO string form.
 *
 * ## Encoding
 * Only substituted **leaf** values are percent-encoded (`rawurlencode`); the token separator `|` and
 * the OR separator `,` are emitted raw, and literal template text outside `{{ }}` is never re-encoded.
 * System URIs in tokens are emitted verbatim (they are query-legal and FHIR servers match the literal
 * system). See {@see self::encode()}.
 *
 * ## Empty / null substitution policy
 * The spec is silent on empty results. This resolver **drops the entire search parameter** whose value
 * contains a `{{ }}` that resolves to empty. NOTE: dropping a scoping parameter (e.g. `subject=`) widens
 * the query — a search intended for one subject becomes unscoped. That is a correctness/PHI-broadening
 * hazard the caller must be aware of; it is flagged forward to the security milestone (M04).
 */
final class XFhirQueryResolver
{
    public function __construct(
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
    ) {
    }

    /**
     * Resolve an x-fhir-query template to a concrete FHIR search string.
     *
     * Holes are evaluated with a null focus resource — launch-context resources are expected to be bound
     * on the {@see EvaluationContext} as external constants (`%patient`, `%user`, …), mirroring how the
     * SDC populate engine binds context.
     *
     * @param string            $template    the x-fhir-query template (e.g. `Observation?subject={{%patient.id}}`)
     * @param EvaluationContext $context     launch context with `%`-constants bound
     * @param string|null       $fhirVersion optional FHIR version hint ('R4', 'R4B', 'R5') passed to the engine
     *
     * @throws \InvalidArgumentException on an unterminated `{{`
     */
    public function resolve(string $template, EvaluationContext $context, ?string $fhirVersion = null): string
    {
        $queryStart = strpos($template, '?');

        if ($queryStart === false) {
            // No query component — resolve holes in the path; an empty hole degrades to an empty string.
            return $this->substitute($template, $context, $fhirVersion) ?? '';
        }

        $path  = substr($template, 0, $queryStart);
        $query = substr($template, $queryStart + 1);

        $resolvedPath = $this->substitute($path, $context, $fhirVersion) ?? '';

        $kept = [];
        foreach ($query === '' ? [] : explode('&', $query) as $param) {
            if ($param === '') {
                continue;
            }

            $equals = strpos($param, '=');
            if ($equals === false) {
                // Valueless parameter (e.g. a bare flag); resolve any holes, drop if it resolves empty.
                $resolved = $this->substitute($param, $context, $fhirVersion);
                if ($resolved !== null) {
                    $kept[] = $resolved;
                }

                continue;
            }

            $name          = substr($param, 0, $equals);
            $valueTemplate = substr($param, $equals + 1);
            $resolvedValue = $this->substitute($valueTemplate, $context, $fhirVersion);

            // Empty-substitution policy: drop the whole parameter (see class docblock — widens the search).
            if ($resolvedValue === null) {
                continue;
            }

            $kept[] = $name . '=' . $resolvedValue;
        }

        // If every parameter was dropped, omit the `?` entirely rather than emit `Observation?` (which is a
        // fetch-everything search — the extreme of the query-broadening hazard documented above).
        if ($kept === []) {
            return $resolvedPath;
        }

        return $resolvedPath . '?' . implode('&', $kept);
    }

    /**
     * Substitute every `{{ … }}` hole in a template segment.
     *
     * @return string|null the segment with holes substituted, or null if any hole resolved to empty
     *                     (the caller decides whether that drops a parameter)
     *
     * @throws \InvalidArgumentException on an unterminated `{{`
     */
    private function substitute(string $segment, EvaluationContext $context, ?string $fhirVersion): ?string
    {
        $out      = '';
        $offset   = 0;
        $hadEmpty = false;

        while (($open = strpos($segment, '{{', $offset)) !== false) {
            $close = strpos($segment, '}}', $open + 2);
            if ($close === false) {
                throw new \InvalidArgumentException(sprintf('Unterminated "{{" in x-fhir-query template segment: %s', $segment));
            }

            $out .= substr($segment, $offset, $open - $offset);
            $expression = trim(substr($segment, $open + 2, $close - $open - 2));

            $formatted = $this->evaluateAndFormat($expression, $context, $fhirVersion);
            if ($formatted === null) {
                $hadEmpty = true;
            } else {
                $out .= $formatted;
            }

            $offset = $close + 2;
        }

        $out .= substr($segment, $offset);

        return $hadEmpty ? null : $out;
    }

    /**
     * Evaluate one FHIRPath expression and format its result as an x-fhir-query token/value.
     *
     * @return string|null the formatted value, or null when the expression yields an empty collection
     */
    private function evaluateAndFormat(string $expression, EvaluationContext $context, ?string $fhirVersion): ?string
    {
        $items = $this->fhirPath->evaluate($expression, null, $context, $fhirVersion)->toArray();

        if ($items === []) {
            return null;
        }

        $tokens = [];
        foreach ($items as $item) {
            $token = $this->formatAtom($item);
            if ($token !== '') {
                $tokens[] = $token;
            }
        }

        // An atom formatting to empty (e.g. an unsupported result type) is dropped like an empty
        // collection would be — per the empty-substitution policy, not kept as a blank/partial token.
        if ($tokens === []) {
            return null;
        }

        // Multiple values → comma-joined "or" list; the comma is a raw separator, not encoded.
        return implode(',', $tokens);
    }

    /**
     * Format a single evaluated value as an x-fhir-query atom.
     */
    private function formatAtom(mixed $item): string
    {
        if (is_bool($item)) {
            return $item ? 'true' : 'false';
        }

        if (is_int($item) || is_float($item)) {
            return $this->encode((string) $item);
        }

        if (is_string($item)) {
            return $this->encode($item);
        }

        if ($item instanceof \Stringable) {
            // FHIRPath temporal types (Date/DateTime/Time) and other Stringables.
            return $this->encode((string) $item);
        }

        if (is_array($item)) {
            return $this->formatComplex($item);
        }

        // Unsupported result type (e.g. resource object) — best effort: skip to an empty token.
        return '';
    }

    /**
     * Format a complex FHIRPath result (associative array) as a token.
     *
     * @param array<string, mixed> $item
     */
    private function formatComplex(array $item): string
    {
        // CodeableConcept → each .coding as a token, comma-joined (spec: behaves as if `.coding` appended).
        if (isset($item['coding']) && is_array($item['coding'])) {
            $tokens = [];
            foreach ($item['coding'] as $coding) {
                if (is_array($coding)) {
                    $tokens[] = $this->tokenFromCoding($coding);
                }
            }

            return implode(',', $tokens);
        }

        // Quantity → value|system|code. Distinguished from Identifier (which also carries `value`) by a
        // `unit` key or a numeric `value` — an Identifier's value is a non-numeric string.
        if (array_key_exists('value', $item)
            && (isset($item['unit']) || is_int($item['value']) || is_float($item['value']))
        ) {
            return $this->quantityToken($item);
        }

        // Coding (system|code) or Identifier (system|value).
        return $this->tokenFromCoding($item);
    }

    /**
     * Build a token from a Coding (`system|code`) or Identifier (`system|value`).
     *
     * Follows FHIR token-search semantics, where the three forms are distinct searches:
     * - `system|code` — that code in that system;
     * - `system|` — any code in that system (code absent; the trailing `|` is kept);
     * - `code` — that code in any system (system absent; NO leading `|`, since `|code` means "no system").
     *
     * @param array<string, mixed> $coding
     */
    private function tokenFromCoding(array $coding): string
    {
        $system = isset($coding['system']) && is_scalar($coding['system']) ? (string) $coding['system'] : '';

        $code = '';
        if (isset($coding['code']) && is_scalar($coding['code'])) {
            $code = (string) $coding['code'];
        } elseif (isset($coding['value']) && is_scalar($coding['value'])) {
            $code = (string) $coding['value'];
        }

        // system verbatim (query-legal URI); code encoded; raw `|` separator.
        if ($system !== '' && $code !== '') {
            return $system . '|' . $this->encode($code);
        }

        if ($system !== '') {
            return $system . '|';
        }

        return $this->encode($code);
    }

    /**
     * Build a Quantity token (`value|system|code`), trimming trailing empty segments per FHIR quantity
     * search: `5|http://unitsofmeasure.org|mg`, `5||mg` (no system), or bare `5`.
     *
     * @param array<string, mixed> $quantity
     */
    private function quantityToken(array $quantity): string
    {
        $value  = isset($quantity['value'])  && is_scalar($quantity['value']) ? $this->encode((string) $quantity['value']) : '';
        $system = isset($quantity['system']) && is_scalar($quantity['system']) ? (string) $quantity['system'] : '';
        $code   = isset($quantity['code'])   && is_scalar($quantity['code']) ? $this->encode((string) $quantity['code']) : '';

        $segments = [$value, $system, $code];

        // Drop trailing empty segments (bare value → `5`); an internal empty is preserved (`5||mg`).
        while ($segments !== [] && end($segments) === '') {
            array_pop($segments);
        }

        return implode('|', $segments);
    }

    /**
     * Percent-encode a substituted leaf value so it is a valid URL query token.
     */
    private function encode(string $value): string
    {
        return rawurlencode($value);
    }
}

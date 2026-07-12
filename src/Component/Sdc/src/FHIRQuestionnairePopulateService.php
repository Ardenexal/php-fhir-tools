<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;

/**
 * Expression-based `Questionnaire/$populate` (M01 scope).
 *
 * Binds each launch-context resource ({@see PopulateContext::$launchContextResources}) as a FHIRPath
 * external constant (`%patient`, …), walks the Questionnaire's items depth-first, evaluates every leaf
 * item's `initialExpression` against that context, and assembles a `QuestionnaireResponse`. Group items
 * that contain answered descendants are reproduced as nested response items; items that yield neither an
 * answer nor an answered descendant are omitted (matching the reference engine's output).
 *
 * ## Guardrails proven by the M01 linchpin spikes
 *
 * Every Questionnaire input is deserializer-origin at runtime, so all extension reads go through
 * {@see SafeExtensionReader} (guarded against uninitialized typed properties on constructor-bypassed
 * objects) rather than bare `$ext->url`/`$ext->value`. External constants are stored in a dedicated
 * `EvaluationContext` slot that survives the `setRootResource()` mutation `FHIRPathService::evaluate()`
 * applies, so one bound context is reused across every item's evaluation.
 *
 * ## Deferred to M02/M03 (see the sdc-populate plan)
 *
 * `variable` chains, `itemPopulationContext` repeating groups, observation-based population,
 * `enableWhen` suppression, full item-type coercion, and non-FHIRPath expression languages
 * (CQL, `x-fhir-query`). Unsupported item types and non-FHIRPath expressions surface as issues rather
 * than silently dropping an answer.
 */
final class FHIRQuestionnairePopulateService implements PopulateServiceInterface
{
    private const string LAUNCH_CONTEXT_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-launchContext';

    private const string INITIAL_EXPRESSION_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression';

    private const string LAUNCH_CONTEXT_NAME_URL = 'name';

    private const string FHIRPATH_LANGUAGE = 'text/fhirpath';

    public function __construct(
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
        private readonly SafeExtensionReader $extensions = new SafeExtensionReader(),
    ) {
    }

    public function populate(object $questionnaire, PopulateContext $context): PopulateResult
    {
        $factory = new PopulateModelFactory($context->fhirVersion);

        /** @var list<object> $issues */
        $issues = [];

        $evalContext = $this->bindLaunchContext($questionnaire, $context, $factory, $issues);

        $responseItems = [];
        foreach ($this->itemsOf($questionnaire) as $item) {
            $built = $this->buildResponseItem($item, $evalContext, $factory, $issues);
            if ($built !== null) {
                $responseItems[] = $built;
            }
        }

        $response = $factory->questionnaireResponse(
            canonical: $this->canonicalUrlOf($questionnaire),
            status: 'in-progress',
            subject: $context->subject !== null ? $factory->reference($context->subject) : null,
            authored: $this->nowInstant(),
            items: $responseItems,
        );

        $outcome = $issues === [] ? null : $factory->operationOutcome($issues);

        return new PopulateResult($response, $outcome);
    }

    /**
     * Build an evaluation context with every supplied launch-context resource bound as `%<name>`, and
     * raise an informational issue for each `launchContext` the Questionnaire declares but the caller did
     * not supply (its `%<name>` expressions will simply resolve empty).
     *
     * @param list<object> $issues
     */
    private function bindLaunchContext(object $questionnaire, PopulateContext $context, PopulateModelFactory $factory, array &$issues): EvaluationContext
    {
        $evalContext = new EvaluationContext();
        foreach ($context->launchContextResources as $name => $resource) {
            $evalContext = $evalContext->withExternalConstant($name, $resource);
        }

        foreach ($this->declaredLaunchContextNames($questionnaire) as $declaredName) {
            if (!array_key_exists($declaredName, $context->launchContextResources)) {
                $issues[] = $factory->issue(
                    IssueSeverity::information->value,
                    IssueType::informationalnote->value,
                    \sprintf(
                        "launchContext '%s' is declared by the Questionnaire but no resource was supplied; "
                        . 'expressions referencing %%%s resolve to empty.',
                        $declaredName,
                        $declaredName,
                    ),
                );
            }
        }

        return $evalContext;
    }

    /**
     * Build a `QuestionnaireResponse.item` for a Questionnaire item, or null when it yields neither an
     * answer nor an answered descendant (such items are omitted, matching the reference output).
     *
     * @param list<object> $issues
     */
    private function buildResponseItem(object $item, EvaluationContext $evalContext, PopulateModelFactory $factory, array &$issues): ?object
    {
        $linkId = $this->stringify($item->linkId ?? null);
        if ($linkId === null) {
            return null;
        }

        $itemType = $this->codeOf($item->type ?? null);

        $answers    = [];
        $expression = $this->initialExpressionOf($item, $factory, $issues, $linkId);
        if ($expression !== null) {
            try {
                $values = $this->fhirPath
                    ->evaluate($expression, null, $evalContext, $factory->fhirVersionValue())
                    ->toArray();
            } catch (\Throwable $e) {
                // A malformed expression, or one referencing an unbound launch context (`%patient` when
                // no Patient was supplied), raises rather than returning empty. Degrade to a warning so a
                // single bad item cannot abort the whole populate. (Full malformed-expression handling: M03.)
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf("initialExpression for item '%s' could not be evaluated: %s", $linkId, $e->getMessage()),
                );

                $values = [];
            }

            if ($values === []) {
                // Observable, not silent: a launch-context-bound expression that resolves to nothing must
                // be distinguishable from a legitimately-unanswered item (M03's "empty ≠ false" rule).
                $issues[] = $factory->issue(
                    IssueSeverity::information->value,
                    IssueType::informationalnote->value,
                    \sprintf("initialExpression for item '%s' returned no value; item left unanswered.", $linkId),
                );
            }

            foreach ($values as $value) {
                $coerced = $this->coerceAnswerValue($itemType, $value, $linkId, $factory, $issues);
                if ($coerced !== null) {
                    $answers[] = $factory->answer($coerced);
                }
            }
        }

        $childItems = [];
        foreach ($this->itemsOf($item) as $child) {
            $builtChild = $this->buildResponseItem($child, $evalContext, $factory, $issues);
            if ($builtChild !== null) {
                $childItems[] = $builtChild;
            }
        }

        if ($answers === [] && $childItems === []) {
            return null;
        }

        return $factory->responseItem($linkId, $this->stringify($item->text ?? null), $answers, $childItems);
    }

    /**
     * Coerce an evaluated scalar to the answer `value[x]` shape for the item's type, or null (with a
     * warning issue) when the type's coercion is not yet supported. M01 covers the primitive scalar
     * types the expression engine returns directly; full coercion (temporal, Coding, Quantity, Reference)
     * lands in M02.
     *
     * @param list<object> $issues
     */
    private function coerceAnswerValue(?string $itemType, mixed $value, string $linkId, PopulateModelFactory $factory, array &$issues): mixed
    {
        $scalar = $this->stringify($value);

        switch ($itemType) {
            case 'string':
            case 'text':
                return $scalar !== null ? $factory->stringValue($scalar) : null;
            case 'url':
                return $scalar !== null ? $factory->uriValue($scalar) : null;
            case 'boolean':
                return \is_bool($value) ? $value : null;
            case 'integer':
                return \is_int($value) ? $value : (\is_numeric($scalar) ? (int) $scalar : null);
            case 'decimal':
                // The `decimal` answer variant is a raw string (→ `valueDecimal`), distinct from the
                // `StringPrimitive` `string` variant (→ `valueString`).
                return $scalar;
            default:
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf(
                        "initialExpression for item '%s' produced a value for item type '%s', whose answer "
                        . 'coercion is not yet supported; the answer was skipped.',
                        $linkId,
                        $itemType ?? '(none)',
                    ),
                );

                return null;
        }
    }

    /**
     * Read an item's `initialExpression` FHIRPath string, or null when absent, unreadable, or expressed
     * in a non-FHIRPath language (CQL / `x-fhir-query` are deferred — reported as a warning).
     *
     * @param list<object> $issues
     */
    private function initialExpressionOf(object $item, PopulateModelFactory $factory, array &$issues, string $linkId): ?string
    {
        $extensions = $item->extension ?? null;
        if (!\is_array($extensions)) {
            return null;
        }

        foreach ($extensions as $ext) {
            if (!\is_object($ext) || $this->extensions->readUrl($ext) !== self::INITIAL_EXPRESSION_URL) {
                continue;
            }

            $expressionValue = $this->extensions->readValue($ext);
            if (!\is_object($expressionValue)) {
                return null;
            }

            $language = $this->stringify($expressionValue->language ?? null);
            if ($language !== null && $language !== self::FHIRPATH_LANGUAGE) {
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf(
                        "initialExpression for item '%s' uses expression language '%s'; only '%s' is "
                        . 'supported (CQL / x-fhir-query are deferred). The item was left unanswered.',
                        $linkId,
                        $language,
                        self::FHIRPATH_LANGUAGE,
                    ),
                );

                return null;
            }

            return $this->stringify($expressionValue->expression ?? null);
        }

        return null;
    }

    /**
     * The SDC `launchContext` names the Questionnaire declares (its root `launchContext` extensions'
     * `name` sub-extension), so a caller that omits a declared context can be told.
     *
     * @return list<string>
     */
    private function declaredLaunchContextNames(object $questionnaire): array
    {
        $extensions = $questionnaire->extension ?? null;
        if (!\is_array($extensions)) {
            return [];
        }

        $names = [];
        foreach ($extensions as $ext) {
            if (!\is_object($ext) || $this->extensions->readUrl($ext) !== self::LAUNCH_CONTEXT_URL) {
                continue;
            }

            $nameExt = $this->extensions->findExtension($ext, self::LAUNCH_CONTEXT_NAME_URL);
            if ($nameExt === null) {
                continue;
            }

            $name = $this->launchContextName($this->extensions->readValue($nameExt));
            if ($name !== null) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Read a launchContext `name` value — a `Coding` (SDC ≥ current), an `id`, or a bare string, per the
     * IG version — down to its code string.
     */
    private function launchContextName(mixed $value): ?string
    {
        if (\is_string($value)) {
            return $value === '' ? null : $value;
        }

        if (\is_object($value)) {
            // Coding: read its `.code`.
            if (property_exists($value, 'code')) {
                $code = $this->stringify($value->code ?? null);
                if ($code !== null) {
                    return $code;
                }
            }

            // id / string primitive wrapper.
            return $this->stringify($value);
        }

        return null;
    }

    /**
     * The child items of a Questionnaire (or Questionnaire item), filtered to objects.
     *
     * @return list<object>
     */
    private function itemsOf(object $node): array
    {
        $items = $node->item ?? null;
        if (!\is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, static fn (mixed $i): bool => \is_object($i)));
    }

    /**
     * The Questionnaire's canonical `url` as a string, or null.
     */
    private function canonicalUrlOf(object $questionnaire): ?string
    {
        return $this->stringify($questionnaire->url ?? null);
    }

    /**
     * Read a code-type wrapper (`QuestionnaireItemTypeType` etc.) down to its string code, or null.
     */
    private function codeOf(mixed $type): ?string
    {
        if ($type === null) {
            return null;
        }

        if (\is_object($type) && property_exists($type, 'value')) {
            $inner = $type->value ?? null;
            if ($inner instanceof \BackedEnum) {
                return (string) $inner->value;
            }

            return \is_string($inner) && $inner !== '' ? $inner : null;
        }

        return $this->stringify($type);
    }

    /**
     * Coerce a primitive-wrapper-or-scalar value to a plain string, tolerating a constructor-bypassed
     * object (uninitialized `value` read via `isset`), or null when unreadable/empty.
     */
    private function stringify(mixed $value): ?string
    {
        if (\is_string($value)) {
            return $value === '' ? null : $value;
        }

        if (\is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (\is_int($value) || \is_float($value)) {
            return (string) $value;
        }

        if (\is_object($value) && property_exists($value, 'value')) {
            $inner = $value->value ?? null;

            if (\is_string($inner)) {
                return $inner === '' ? null : $inner;
            }
            if (\is_object($inner) && method_exists($inner, '__toString')) {
                $string = (string) $inner;

                return $string === '' ? null : $string;
            }
        }

        return null;
    }

    /**
     * Current UTC instant as an ISO-8601 dateTime string for `QuestionnaireResponse.authored`.
     */
    private function nowInstant(): string
    {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
}

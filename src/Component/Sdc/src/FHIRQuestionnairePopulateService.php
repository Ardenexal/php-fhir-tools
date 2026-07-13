<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireResolverInterface;

/**
 * Expression-based `Questionnaire/$populate` (M01 + M02 scope).
 *
 * Binds each launch-context resource ({@see PopulateContext::$launchContextResources}) as a FHIRPath
 * external constant (`%patient`, …), resolves `variable` chains into further external constants, walks
 * the Questionnaire's items depth-first evaluating each item's `initialExpression`, and assembles a
 * `QuestionnaireResponse`. Group items carrying an `itemPopulationContext` are repeated once per context
 * result, with the context name bound to each result for the repetition's descendants. Items that yield
 * neither an answer nor an answered descendant are omitted (matching the reference engine's output).
 * Items with an `observationLinkPeriod` (and no `initialExpression`) are populated from the most-recent
 * eligible matching `Observation` supplied via {@see PopulateContext::$dataProvider} (offline-first).
 *
 * ## Design facts (proven against the reference engine — see `tests/SOURCES.md`)
 *
 * - **`enableWhen` is NOT applied.** The SDC `$populate` spec directs implementations to "fill in as much
 *   data as possible, even if it may not always be needed"; disabled-state is a display-time concern. So
 *   `enableWhen`-disabled items are still populated.
 * - **`itemPopulationContext`** repeats a group once per context result; `%<name>` binds to each result.
 * - **Empty ≠ false.** An `initialExpression` that resolves to empty yields no answer (an information
 *   issue), never a `false`/default value — the spec mandates the empty set be treated as "not answered".
 *
 * ## Guardrails
 *
 * Every Questionnaire input is deserializer-origin at runtime, so all extension reads go through
 * {@see SafeExtensionReader} (guarded against uninitialized typed properties on constructor-bypassed
 * objects). External constants live in a dedicated `EvaluationContext` slot that survives the
 * `setRootResource()` mutation `FHIRPathService::evaluate()` applies, so a bound context is reused across
 * evaluations; each scope (root, item, repetition) derives an immutable child context.
 *
 * ## Deferred (see the sdc-populate plan / backlog)
 *
 * Binding-driven `code`→`Coding` promotion (a bare code systematised via the item's value-set binding)
 * and non-FHIRPath expression languages (CQL, `x-fhir-query`). Unsupported item types and non-FHIRPath
 * expressions surface as issues. Answer coercion covers the primitive, temporal, and
 * `Coding`/`Quantity`/`Reference` datatypes; canonical-URL resolution is wired via
 * {@see FHIRQuestionnaireResolverInterface}; observation-based population reads a supplied data provider.
 */
final class FHIRQuestionnairePopulateService implements PopulateServiceInterface
{
    private const string LAUNCH_CONTEXT_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-launchContext';

    private const string INITIAL_EXPRESSION_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-initialExpression';

    private const string VARIABLE_URL = 'http://hl7.org/fhir/StructureDefinition/variable';

    private const string ITEM_POPULATION_CONTEXT_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-itemPopulationContext';

    private const string OBSERVATION_LINK_PERIOD_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-observationLinkPeriod';

    private const string LAUNCH_CONTEXT_NAME_URL = 'name';

    private const string FHIRPATH_LANGUAGE = 'text/fhirpath';

    /**
     * Observation statuses eligible for observation-based population — a completed/authoritative result.
     * (SDC: preliminary/registered/cancelled/entered-in-error are excluded.)
     *
     * @var list<string>
     */
    private const array OBSERVATION_STATUSES = ['final', 'amended', 'corrected'];

    public function __construct(
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
        private readonly SafeExtensionReader $extensions = new SafeExtensionReader(),
        /**
         * Resolves a canonical URL passed to {@see populate()} to a Questionnaire resource. Optional — when
         * null, a string `$questionnaire` cannot be resolved and yields an empty QR plus a warning. NB the
         * resolver interface is R5-typed by existing design; the resolved Questionnaire is read
         * version-agnostically, so it populates a QR of whatever {@see PopulateContext::$fhirVersion} asks.
         */
        private readonly ?FHIRQuestionnaireResolverInterface $questionnaireResolver = null,
    ) {
    }

    public function populate(object|string $questionnaire, PopulateContext $context): PopulateResult
    {
        $factory = new PopulateModelFactory($context->fhirVersion);

        /** @var list<object> $issues */
        $issues = [];

        $resolved = $this->resolveQuestionnaire($questionnaire, $factory, $issues);
        if ($resolved === null) {
            // Canonical URL could not be resolved (no resolver, or not found): return an empty in-progress
            // QR carrying the requested canonical plus the warning, rather than throwing.
            $response = $factory->questionnaireResponse(
                canonical: \is_string($questionnaire) ? $questionnaire : null,
                status: 'in-progress',
                subject: $context->subject !== null ? $factory->reference($context->subject) : null,
                authored: $this->nowInstant(),
                items: [],
            );

            return new PopulateResult($response, $factory->operationOutcome($issues));
        }
        $questionnaire = $resolved;

        $evalContext = $this->bindLaunchContext($questionnaire, $context, $factory, $issues);

        // Root-level `variable` extensions become external constants visible to every item expression.
        $evalContext = $this->applyVariables($questionnaire, $evalContext, $factory, $issues);

        $responseItems = $this->buildItems($this->itemsOf($questionnaire), $evalContext, $context, $factory, $issues);

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
     * Resolve the `$questionnaire` argument to a Questionnaire object: pass a model object through
     * unchanged; resolve a canonical URL string via the configured resolver. Returns null (with a warning
     * issue) when a string cannot be resolved — no resolver configured, or the URL is not found.
     *
     * @param list<object> $issues
     */
    private function resolveQuestionnaire(object|string $questionnaire, PopulateModelFactory $factory, array &$issues): ?object
    {
        if (\is_object($questionnaire)) {
            return $questionnaire;
        }

        if ($this->questionnaireResolver === null) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf(
                    "A canonical Questionnaire URL '%s' was supplied but no resolver is configured; cannot populate.",
                    $questionnaire,
                ),
            );

            return null;
        }

        $resolved = $this->questionnaireResolver->resolve($questionnaire);
        if ($resolved === null) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf("Questionnaire canonical URL '%s' could not be resolved; cannot populate.", $questionnaire),
            );

            return null;
        }

        return $resolved;
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
     * Resolve the `variable` extensions declared on a node (Questionnaire root or item) in declaration
     * order, binding each result as an external constant `%<name>` available to later variables and to
     * descendant expressions. Returns a new context; the input is not mutated.
     *
     * Multi-valued variables are bound to their first value (the FHIRPath engine resolves an external
     * constant as a single node); a warning records the truncation. Repeating semantics belong to
     * `itemPopulationContext`, not `variable`.
     *
     * @param list<object> $issues
     */
    private function applyVariables(object $node, EvaluationContext $evalContext, PopulateModelFactory $factory, array &$issues): EvaluationContext
    {
        foreach ($this->extensionsOf($node) as $ext) {
            if ($this->extensions->readUrl($ext) !== self::VARIABLE_URL) {
                continue;
            }

            $definition = $this->namedExpressionOf($ext, $factory, $issues, 'variable');
            if ($definition === null) {
                continue;
            }
            [$name, $expression] = $definition;

            $values = $this->evaluateExpression($expression, $evalContext, $factory, $issues, \sprintf("variable '%s'", $name));
            if ($values === []) {
                continue;
            }

            if (\count($values) > 1) {
                $issues[] = $factory->issue(
                    IssueSeverity::information->value,
                    IssueType::informationalnote->value,
                    \sprintf("variable '%s' resolved to %d values; only the first is bound.", $name, \count($values)),
                );
            }

            $evalContext = $evalContext->withExternalConstant($name, $values[0]);
        }

        return $evalContext;
    }

    /**
     * Build the response items for a list of Questionnaire items, expanding any `itemPopulationContext`
     * group into one repetition per context result.
     *
     * @param list<object> $items
     * @param list<object> $issues
     *
     * @return list<object>
     */
    private function buildItems(array $items, EvaluationContext $evalContext, PopulateContext $populateContext, PopulateModelFactory $factory, array &$issues): array
    {
        $result = [];

        foreach ($items as $item) {
            // Item-level `variable`s extend the context for this item and its descendants.
            $itemContext = $this->applyVariables($item, $evalContext, $factory, $issues);

            $populationContext = $this->itemPopulationContextOf($item, $factory, $issues);
            if ($populationContext === null) {
                $built = $this->buildOneItem($item, $itemContext, $populateContext, $factory, $issues);
                if ($built !== null) {
                    $result[] = $built;
                }

                continue;
            }

            // itemPopulationContext: one group repetition per context result, `%<name>` bound to each.
            [$contextName, $contextExpression] = $populationContext;
            $contextResults                    = $this->evaluateExpression(
                $contextExpression,
                $itemContext,
                $factory,
                $issues,
                \sprintf("itemPopulationContext '%s'", $contextName),
            );

            foreach ($contextResults as $contextResult) {
                $repetitionContext = $itemContext->withExternalConstant($contextName, $contextResult);
                $built             = $this->buildOneItem($item, $repetitionContext, $populateContext, $factory, $issues);
                if ($built !== null) {
                    $result[] = $built;
                }
            }
        }

        return $result;
    }

    /**
     * Build a single `QuestionnaireResponse.item` from a Questionnaire item in the given context, or null
     * when it yields neither an answer nor an answered descendant (such items are omitted).
     *
     * @param list<object> $issues
     */
    private function buildOneItem(object $item, EvaluationContext $evalContext, PopulateContext $populateContext, PopulateModelFactory $factory, array &$issues): ?object
    {
        $linkId = $this->stringify($item->linkId ?? null);
        if ($linkId === null) {
            return null;
        }

        $itemType = $this->codeOf($item->type ?? null);

        $answers    = [];
        $expression = $this->initialExpressionOf($item, $factory, $issues, $linkId);
        if ($expression === null && $this->hasObservationLinkPeriod($item)) {
            // Observation-based population is an alternative to expression-based; it applies only when the
            // item has no initialExpression. Populates from the most-recent matching supplied Observation.
            $answers = $this->populateFromObservation($item, $itemType, $linkId, $populateContext, $factory, $issues);
        } elseif ($expression !== null) {
            $values = $this->evaluateExpression($expression, $evalContext, $factory, $issues, \sprintf("initialExpression for item '%s'", $linkId));

            if ($values === []) {
                // Observable, not silent: a launch-context-bound expression that resolves to nothing must
                // be distinguishable from a legitimately-unanswered item ("empty set = not answered").
                $issues[] = $factory->issue(
                    IssueSeverity::information->value,
                    IssueType::informationalnote->value,
                    \sprintf("initialExpression for item '%s' returned no value; item left unanswered.", $linkId),
                );
            } elseif (\count($values) > 1 && !$this->isRepeating($item)) {
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf(
                        "initialExpression for item '%s' produced %d values but the item does not repeat; only the first is used.",
                        $linkId,
                        \count($values),
                    ),
                );
                $values = [$values[0]];
            }

            foreach ($values as $value) {
                $coerced = $this->coerceAnswerValue($itemType, $value, $linkId, $factory, $issues);
                if ($coerced !== null) {
                    $answers[] = $factory->answer($coerced);
                }
            }
        }

        $childItems = $this->buildItems($this->itemsOf($item), $evalContext, $populateContext, $factory, $issues);

        if ($answers === [] && $childItems === []) {
            return null;
        }

        return $factory->responseItem($linkId, $this->stringify($item->text ?? null), $answers, $childItems);
    }

    /**
     * Evaluate a FHIRPath expression against the given context, degrading a raised error (malformed
     * expression, unbound external constant) to a warning issue and an empty result rather than aborting
     * the whole run.
     *
     * @param list<object> $issues
     *
     * @return list<mixed>
     */
    private function evaluateExpression(string $expression, EvaluationContext $evalContext, PopulateModelFactory $factory, array &$issues, string $label): array
    {
        try {
            return $this->fhirPath
                ->evaluate($expression, null, $evalContext, $factory->fhirVersionValue())
                ->toArray();
        } catch (\Throwable $e) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf('%s could not be evaluated: %s', ucfirst($label), $e->getMessage()),
            );

            return [];
        }
    }

    /**
     * Coerce an evaluated value to the answer `value[x]` shape for the item's type, or null (with a
     * warning issue) when the value cannot be coerced. A type mismatch is reported, never silently dropped.
     *
     * @param list<object> $issues
     */
    private function coerceAnswerValue(?string $itemType, mixed $value, string $linkId, PopulateModelFactory $factory, array &$issues): mixed
    {
        $scalar = $this->stringify($value);

        switch ($itemType) {
            case 'string':
            case 'text':
                return $scalar !== null ? $factory->stringValue($scalar) : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'url':
                return $scalar !== null ? $factory->uriValue($scalar) : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'boolean':
                return \is_bool($value) ? $value : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'integer':
                if (\is_int($value)) {
                    return $value;
                }

                return \is_string($scalar) && $this->isIntegerString($scalar) ? (int) $scalar : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'decimal':
                // The `decimal` answer variant is a raw string (→ `valueDecimal`), distinct from the
                // `StringPrimitive` `string` variant (→ `valueString`).
                return $scalar !== null && is_numeric($scalar) ? $scalar : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'date':
            case 'dateTime':
            case 'time':
                return $scalar !== null
                    ? $this->coerceTemporal($itemType, $scalar, $linkId, $factory, $issues)
                    : $this->mismatch($itemType, $linkId, $factory, $issues);
            case 'choice':
            case 'open-choice':
            case 'quantity':
            case 'reference':
            case 'attachment':
                // Strict-by-source-datatype (matching the reference engine): the expression must already
                // resolve to the right FHIR datatype OBJECT (`Coding`/`Quantity`/`Reference`/`Attachment`).
                // The answer choice normalizer maps the object's class to the correct `value[x]` key, so the
                // object is passed through intact. A bare scalar for a complex item is a mismatch (the engine
                // rejects it), never silently coerced. Binding-driven `code`→`Coding` promotion (a bare code
                // systematised via the item's value-set binding) is deferred — see the sdc-populate backlog.
                return \is_object($value) ? $value : $this->mismatch($itemType, $linkId, $factory, $issues);
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
     * Wrap a scalar into a temporal primitive (`date`/`dateTime`/`time`), reporting a mismatch when the
     * scalar cannot be parsed as that FHIR temporal type.
     *
     * @param list<object> $issues
     */
    private function coerceTemporal(string $itemType, string $scalar, string $linkId, PopulateModelFactory $factory, array &$issues): mixed
    {
        try {
            return match ($itemType) {
                'date'     => $factory->dateValue($scalar),
                'dateTime' => $factory->dateTimeValue($scalar),
                default    => $factory->timeValue($scalar),
            };
        } catch (\Throwable) {
            return $this->mismatch($itemType, $linkId, $factory, $issues, \sprintf('value "%s" is not a valid %s', $scalar, $itemType));
        }
    }

    /**
     * Record a coercion mismatch as a warning and return null (no answer). Centralises the
     * "observable, not silent" discipline for every failed coercion branch.
     *
     * @param list<object> $issues
     */
    private function mismatch(?string $itemType, string $linkId, PopulateModelFactory $factory, array &$issues, ?string $detail = null): null
    {
        $issues[] = $factory->issue(
            IssueSeverity::warning->value,
            IssueType::processingfailure->value,
            \sprintf(
                "initialExpression for item '%s' produced a value incompatible with item type '%s'%s; the answer was skipped.",
                $linkId,
                $itemType ?? '(none)',
                $detail !== null ? ' (' . $detail . ')' : '',
            ),
        );

        return null;
    }

    /**
     * Whether an item carries an `observationLinkPeriod` extension (observation-based population).
     */
    private function hasObservationLinkPeriod(object $item): bool
    {
        foreach ($this->extensionsOf($item) as $ext) {
            if ($this->extensions->readUrl($ext) === self::OBSERVATION_LINK_PERIOD_URL) {
                return true;
            }
        }

        return false;
    }

    /**
     * Populate an item from the most-recent supplied `Observation` that matches the item's `code`, has an
     * eligible status, and whose effective time falls within the `observationLinkPeriod` window. Returns
     * the answers (0 or 1); records an information/warning issue when the data seam is absent, the item has
     * no code, or nothing matches. No reference oracle exists for this mechanism (see `tests/SOURCES.md`);
     * the selection is spec-driven and covered by deterministic unit tests.
     *
     * @param list<object> $issues
     *
     * @return list<object>
     */
    private function populateFromObservation(object $item, ?string $itemType, string $linkId, PopulateContext $populateContext, PopulateModelFactory $factory, array &$issues): array
    {
        $provider = $populateContext->dataProvider;
        if ($provider === null) {
            $issues[] = $factory->issue(
                IssueSeverity::information->value,
                IssueType::informationalnote->value,
                \sprintf("Item '%s' uses observationLinkPeriod but no data provider was supplied; item left unanswered.", $linkId),
            );

            return [];
        }

        $itemKeys = $this->itemCodingKeys($item);
        if ($itemKeys === []) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf("Item '%s' has observationLinkPeriod but no item.code to match Observations against; item left unanswered.", $linkId),
            );

            return [];
        }

        [$windowStart, $windowEnd] = $this->observationWindow($item, $linkId, $factory, $issues);

        $best   = null;
        $bestTs = null;
        foreach ($provider->observations() as $observation) {
            if (!$this->observationStatusEligible($observation) || !$this->observationMatchesCodes($observation, $itemKeys)) {
                continue;
            }

            $timestamp = $this->observationEffectiveTimestamp($observation);
            if ($timestamp === null
                || ($windowStart !== null && $timestamp < $windowStart)
                || ($windowEnd !== null && $timestamp > $windowEnd)) {
                continue;
            }

            if ($bestTs === null || $timestamp > $bestTs) {
                $best   = $observation;
                $bestTs = $timestamp;
            }
        }

        if ($best === null) {
            $issues[] = $factory->issue(
                IssueSeverity::information->value,
                IssueType::informationalnote->value,
                \sprintf("No matching Observation for item '%s' within its observationLinkPeriod; item left unanswered.", $linkId),
            );

            return [];
        }

        $value = $best->value ?? null;
        if ($value === null) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf("The Observation matched for item '%s' carries no value; item left unanswered.", $linkId),
            );

            return [];
        }

        $coerced = $this->coerceAnswerValue($itemType, $value, $linkId, $factory, $issues);

        return $coerced !== null ? [$factory->answer($coerced)] : [];
    }

    /**
     * The `[start, end]` Unix-timestamp bounds of an item's `observationLinkPeriod`, either bound null when
     * unbounded. A `Period` gives explicit start/end; a `Duration` gives `[now - duration, now]`. When a
     * `Duration` amount or UCUM unit cannot be mapped, the look-back is treated as unbounded and a warning
     * issue is recorded, so the widened window is observable rather than a silent stale-value selection.
     *
     * @param list<object> $issues
     *
     * @return array{0: int|null, 1: int|null}
     */
    private function observationWindow(object $item, string $linkId, PopulateModelFactory $factory, array &$issues): array
    {
        $value = null;
        foreach ($this->extensionsOf($item) as $ext) {
            if ($this->extensions->readUrl($ext) === self::OBSERVATION_LINK_PERIOD_URL) {
                $value = $this->extensions->readValue($ext);
                break;
            }
        }

        if (!\is_object($value)) {
            return [null, null];
        }

        // Period (has start/end declared) → explicit bounds.
        if (property_exists($value, 'start') || property_exists($value, 'end')) {
            return [
                $this->parseTimestamp($this->stringify($value->start ?? null)),
                $this->parseTimestamp($this->stringify($value->end ?? null)),
            ];
        }

        // Duration (has value/unit/code) → look back from now.
        if (property_exists($value, 'value')) {
            $seconds = $this->durationSeconds($value);
            if ($seconds === null) {
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf("Item '%s' observationLinkPeriod Duration has an unrecognised amount or unit; the look-back window was treated as unbounded (the most recent eligible Observation is still used).", $linkId),
                );
            }

            return [$seconds !== null ? time() - $seconds : null, time()];
        }

        return [null, null];
    }

    /**
     * Approximate a `Duration` as whole seconds (year≈365d, month≈30d — adequate for a look-back window),
     * or null when the amount or UCUM unit/code is unrecognised.
     */
    private function durationSeconds(object $duration): ?int
    {
        $amount = $this->stringify($duration->value ?? null);
        if ($amount === null || !is_numeric($amount)) {
            return null;
        }

        $unit    = $this->stringify($duration->code ?? null) ?? $this->stringify($duration->unit ?? null);
        $perUnit = match ($unit) {
            'a', 'year', 'years'       => 365 * 86400,
            'mo', 'month', 'months'    => 30  * 86400,
            'wk', 'week', 'weeks'      => 7   * 86400,
            'd', 'day', 'days'         => 86400,
            'h', 'hour', 'hours'       => 3600,
            'min', 'minute', 'minutes' => 60,
            's', 'second', 'seconds'   => 1,
            default                    => null,
        };

        return $perUnit !== null ? (int) round((float) $amount * $perUnit) : null;
    }

    /**
     * Whether an Observation's status is eligible for population (final / amended / corrected).
     */
    private function observationStatusEligible(object $observation): bool
    {
        $status = $this->codeOf($observation->status ?? null);

        return $status !== null && \in_array($status, self::OBSERVATION_STATUSES, true);
    }

    /**
     * Whether an Observation's `code.coding` shares any `system|code` key with the item's codes.
     *
     * @param list<string> $itemKeys
     */
    private function observationMatchesCodes(object $observation, array $itemKeys): bool
    {
        $code = $observation->code ?? null;
        if (!\is_object($code)) {
            return false;
        }

        $codings = $code->coding ?? null;
        if (!\is_array($codings)) {
            return false;
        }

        foreach ($codings as $coding) {
            if (\is_object($coding)) {
                $key = $this->codingKey($coding);
                if ($key !== null && \in_array($key, $itemKeys, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * The `system|code` keys of an item's `code` Codings.
     *
     * @return list<string>
     */
    private function itemCodingKeys(object $item): array
    {
        $codes = $item->code ?? null;
        if (!\is_array($codes)) {
            return [];
        }

        $keys = [];
        foreach ($codes as $coding) {
            if (\is_object($coding)) {
                $key = $this->codingKey($coding);
                if ($key !== null) {
                    $keys[] = $key;
                }
            }
        }

        return $keys;
    }

    /**
     * A `system|code` key for a Coding (empty system tolerated), or null when it has no code.
     */
    private function codingKey(object $coding): ?string
    {
        $code = $this->stringify($coding->code ?? null);
        if ($code === null) {
            return null;
        }

        return ($this->stringify($coding->system ?? null) ?? '') . '|' . $code;
    }

    /**
     * The effective Unix timestamp of an Observation (`effectiveDateTime`/`effectiveInstant`, or a
     * `effectivePeriod`'s start/end), or null when unreadable.
     */
    private function observationEffectiveTimestamp(object $observation): ?int
    {
        $effective = $observation->effective ?? null;
        if (!\is_object($effective)) {
            return $this->parseTimestamp($this->stringify($effective));
        }

        if (property_exists($effective, 'start') || property_exists($effective, 'end')) {
            return $this->parseTimestamp($this->stringify($effective->start ?? null))
                ?? $this->parseTimestamp($this->stringify($effective->end ?? null));
        }

        return $this->parseTimestamp($this->stringify($effective));
    }

    /**
     * Parse a FHIR date/dateTime/instant string to a Unix timestamp, or null when absent/unparseable.
     */
    private function parseTimestamp(?string $value): ?int
    {
        if ($value === null) {
            return null;
        }

        try {
            return (new \DateTimeImmutable($value))->getTimestamp();
        } catch (\Throwable) {
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
        foreach ($this->extensionsOf($item) as $ext) {
            if ($this->extensions->readUrl($ext) !== self::INITIAL_EXPRESSION_URL) {
                continue;
            }

            $expressionValue = $this->extensions->readValue($ext);
            if (!\is_object($expressionValue)) {
                return null;
            }

            if (!$this->isFhirPath($expressionValue, $factory, $issues, \sprintf("initialExpression for item '%s'", $linkId))) {
                return null;
            }

            return $this->stringify($expressionValue->expression ?? null);
        }

        return null;
    }

    /**
     * Read the `itemPopulationContext` of a group item as a `[name, expression]` pair, or null when absent.
     *
     * @param list<object> $issues
     *
     * @return array{0: string, 1: string}|null
     */
    private function itemPopulationContextOf(object $item, PopulateModelFactory $factory, array &$issues): ?array
    {
        foreach ($this->extensionsOf($item) as $ext) {
            if ($this->extensions->readUrl($ext) === self::ITEM_POPULATION_CONTEXT_URL) {
                return $this->namedExpressionOf($ext, $factory, $issues, 'itemPopulationContext');
            }
        }

        return null;
    }

    /**
     * Read a named FHIRPath `Expression` (`variable` / `itemPopulationContext` `valueExpression`) as a
     * `[name, expression]` pair, or null when incomplete or in an unsupported language.
     *
     * @param list<object> $issues
     *
     * @return array{0: string, 1: string}|null
     */
    private function namedExpressionOf(object $ext, PopulateModelFactory $factory, array &$issues, string $kind): ?array
    {
        $expressionValue = $this->extensions->readValue($ext);
        if (!\is_object($expressionValue)) {
            return null;
        }

        if (!$this->isFhirPath($expressionValue, $factory, $issues, $kind)) {
            return null;
        }

        $name       = $this->stringify($expressionValue->name ?? null);
        $expression = $this->stringify($expressionValue->expression ?? null);
        if ($name === null || $expression === null) {
            return null;
        }

        return [$name, $expression];
    }

    /**
     * Whether an `Expression`'s language is FHIRPath (or unset, which defaults to FHIRPath here). Emits a
     * warning and returns false for CQL / `x-fhir-query` (deferred).
     *
     * @param list<object> $issues
     */
    private function isFhirPath(object $expressionValue, PopulateModelFactory $factory, array &$issues, string $label): bool
    {
        $language = $this->stringify($expressionValue->language ?? null);
        if ($language !== null && $language !== self::FHIRPATH_LANGUAGE) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf(
                    "%s uses expression language '%s'; only '%s' is supported (CQL / x-fhir-query are deferred).",
                    ucfirst($label),
                    $language,
                    self::FHIRPATH_LANGUAGE,
                ),
            );

            return false;
        }

        return true;
    }

    /**
     * The SDC `launchContext` names the Questionnaire declares (its root `launchContext` extensions'
     * `name` sub-extension), so a caller that omits a declared context can be told.
     *
     * @return list<string>
     */
    private function declaredLaunchContextNames(object $questionnaire): array
    {
        $names = [];
        foreach ($this->extensionsOf($questionnaire) as $ext) {
            if ($this->extensions->readUrl($ext) !== self::LAUNCH_CONTEXT_URL) {
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
     * The `extension[]` of a node, filtered to objects.
     *
     * @return list<object>
     */
    private function extensionsOf(object $node): array
    {
        $extensions = $node->extension ?? null;
        if (!\is_array($extensions)) {
            return [];
        }

        return array_values(array_filter($extensions, static fn (mixed $e): bool => \is_object($e)));
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
     * Whether a Questionnaire item is marked `repeats = true`.
     */
    private function isRepeating(object $item): bool
    {
        $repeats = $item->repeats ?? null;
        if (\is_bool($repeats)) {
            return $repeats;
        }

        if (\is_object($repeats) && property_exists($repeats, 'value')) {
            return ($repeats->value ?? null) === true;
        }

        return false;
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
     * Whether a string is a plain base-10 integer (optionally signed) — used to accept an integer answer
     * the FHIRPath engine returned as a numeric string without misreading a decimal as an integer.
     */
    private function isIntegerString(string $value): bool
    {
        return preg_match('/^[+-]?\d+$/', $value) === 1;
    }

    /**
     * Current UTC instant as an ISO-8601 dateTime string for `QuestionnaireResponse.authored`.
     */
    private function nowInstant(): string
    {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
}

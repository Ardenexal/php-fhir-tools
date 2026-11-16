<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\HttpClient\XFhirQuery\XFhirQueryResolver;
use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Sdc\Contract\PopulateServiceInterface;
use Ardenexal\FHIRTools\Component\Sdc\Contract\QueryPopulationDataProviderInterface;
use Ardenexal\FHIRTools\Component\Sdc\Populate\AnswerValueCoercer;
use Ardenexal\FHIRTools\Component\Sdc\Populate\FhirPrimitiveReader;
use Ardenexal\FHIRTools\Component\Sdc\Populate\ObservationSelector;
use Ardenexal\FHIRTools\Component\Sdc\Populate\PopulateModelFactory;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireResolverInterface;

/**
 * Expression- and observation-based `Questionnaire/$populate`.
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

    private const string LAUNCH_CONTEXT_NAME_URL = 'name';

    private const string FHIRPATH_LANGUAGE = 'text/fhirpath';

    private const string X_FHIR_QUERY_LANGUAGE = 'application/x-fhir-query';

    /**
     * @param FHIRPathService                         $fhirPath              engine evaluating every
     *                                                                       `initialExpression` / `variable` /
     *                                                                       `itemPopulationContext` expression
     * @param SafeExtensionReader                     $extensions            guarded reader for the SDC
     *                                                                       extensions on deserializer-origin
     *                                                                       Questionnaire objects
     * @param FHIRQuestionnaireResolverInterface|null $questionnaireResolver resolves a canonical URL passed to
     *                                                                       {@see populate()} to a
     *                                                                       Questionnaire; optional — when null
     *                                                                       a string `$questionnaire` yields an
     *                                                                       empty QR plus a warning. The
     *                                                                       resolver is R5-typed by existing
     *                                                                       design, but the resolved
     *                                                                       Questionnaire is read
     *                                                                       version-agnostically, so it
     *                                                                       populates a QR of whatever
     *                                                                       {@see PopulateContext::$fhirVersion}
     *                                                                       asks
     * @param FhirPrimitiveReader|null                $primitives            shared primitive-value reader;
     *                                                                       defaults to a new instance
     * @param AnswerValueCoercer|null                 $coercer               answer coercer; defaults to one
     *                                                                       bound to the same $primitives
     * @param ObservationSelector|null                $observations          observation-based population;
     *                                                                       defaults to one bound to the same
     *                                                                       $extensions / $primitives / $coercer
     */
    public function __construct(
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
        private readonly SafeExtensionReader $extensions = new SafeExtensionReader(),
        private readonly ?FHIRQuestionnaireResolverInterface $questionnaireResolver = null,
        ?FhirPrimitiveReader $primitives = null,
        ?AnswerValueCoercer $coercer = null,
        ?ObservationSelector $observations = null,
        ?XFhirQueryResolver $xFhirQueryResolver = null,
    ) {
        $this->primitives         = $primitives        ?? new FhirPrimitiveReader();
        $this->coercer            = $coercer           ?? new AnswerValueCoercer($this->primitives);
        $this->observations       = $observations      ?? new ObservationSelector($this->extensions, $this->primitives, $this->coercer);
        // Pure, offline template resolver — reuses the service's FHIRPath engine (no second engine). Only
        // exercised when a QueryPopulationDataProviderInterface is supplied on the PopulateContext.
        $this->xFhirQueryResolver = $xFhirQueryResolver ?? new XFhirQueryResolver($this->fhirPath);
    }

    private readonly FhirPrimitiveReader $primitives;

    private readonly AnswerValueCoercer $coercer;

    private readonly ObservationSelector $observations;

    private readonly XFhirQueryResolver $xFhirQueryResolver;

    /**
     * Populate a QuestionnaireResponse from a Questionnaire and its launch context.
     *
     * Never throws: an unresolvable canonical URL, a malformed expression, or a missing launch context each
     * degrade to an `OperationOutcome` issue on the returned {@see PopulateResult} while the rest of the form
     * still populates. See the class docblock for the mechanisms applied and their order.
     *
     * @param object|string   $questionnaire a version-specific Questionnaire model carrying the SDC population
     *                                       directives, OR a canonical URL string resolved via the configured
     *                                       {@see FHIRQuestionnaireResolverInterface}
     * @param PopulateContext $context       target version, launch-context resources, subject, and data provider
     *
     * @return PopulateResult the generated QuestionnaireResponse plus any informational/warning issues
     */
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
        $evalContext = $this->applyVariables($questionnaire, $evalContext, $factory, $issues, $context->queryProvider);

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
    private function applyVariables(object $node, EvaluationContext $evalContext, PopulateModelFactory $factory, array &$issues, ?QueryPopulationDataProviderInterface $queryProvider): EvaluationContext
    {
        foreach ($this->extensionsOf($node) as $ext) {
            if ($this->extensions->readUrl($ext) !== self::VARIABLE_URL) {
                continue;
            }

            // x-fhir-query context variable (opt-in): resolve + fetch when a query provider is configured.
            // With no provider this block is skipped and the FHIRPath path below runs unchanged.
            if ($queryProvider !== null) {
                $xq = $this->xFhirQueryContextResults($ext, $evalContext, $factory, $issues, $queryProvider, 'variable');
                if ($xq !== null) {
                    [$name, $values] = $xq;
                    if ($name !== null && $values !== []) {
                        if (\count($values) > 1) {
                            $issues[] = $factory->issue(
                                IssueSeverity::information->value,
                                IssueType::informationalnote->value,
                                \sprintf("variable '%s' resolved to %d values; only the first is bound.", $name, \count($values)),
                            );
                        }

                        $evalContext = $evalContext->withExternalConstant($name, $values[0]);
                    }

                    continue;
                }
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
     * Find the `itemPopulationContext` extension on an item, or null when absent.
     *
     * Mirrors the URL scan in {@see itemPopulationContextOf()} but returns the raw extension (no reading,
     * no warning) so the x-fhir-query pre-check can inspect its Expression language before the FHIRPath path.
     */
    private function itemPopulationContextExtension(object $item): ?object
    {
        foreach ($this->extensionsOf($item) as $ext) {
            if ($this->extensions->readUrl($ext) === self::ITEM_POPULATION_CONTEXT_URL) {
                return $ext;
            }
        }

        return null;
    }

    /**
     * Handle an `application/x-fhir-query` **context** Expression (`variable` / `itemPopulationContext`) on
     * the given extension, resolving the template offline and dispatching it through the supplied provider.
     *
     * Returns null when the extension is not x-fhir-query — the caller then uses its normal FHIRPath path
     * (which also emits the deferred-language warning for CQL). Otherwise returns `[name, resources]`
     * (resources possibly empty), or `[null, []]` when the x-fhir-query is unusable (missing name/expression),
     * so the caller does not fall through to the deferred-language warning for a language it does support.
     *
     * A fetch failure (provider returns null) is a `warning`; an empty match set is handled by the caller as
     * its usual empty-context case. Template resolution is offline; only the provider dispatch is networked.
     *
     * @param list<object> $issues
     *
     * @return array{0: string|null, 1: list<object>}|null
     */
    private function xFhirQueryContextResults(
        object $ext,
        EvaluationContext $evalContext,
        PopulateModelFactory $factory,
        array &$issues,
        QueryPopulationDataProviderInterface $queryProvider,
        string $kind,
    ): ?array {
        $expressionValue = $this->extensions->readValue($ext);
        if (!\is_object($expressionValue)) {
            return null;
        }

        $language = $this->primitives->stringify($expressionValue->language ?? null);
        if ($language !== self::X_FHIR_QUERY_LANGUAGE) {
            return null; // not x-fhir-query — leave it to the FHIRPath path
        }

        $name     = $this->primitives->stringify($expressionValue->name ?? null);
        $template = $this->primitives->stringify($expressionValue->expression ?? null);
        if ($name === null || $template === null) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::invalidcontent->value,
                \sprintf('%s x-fhir-query is missing a name or expression; skipped.', ucfirst($kind)),
            );

            return [null, []];
        }

        try {
            $resolved = $this->xFhirQueryResolver->resolve($template, $evalContext, $factory->fhirVersionValue());
        } catch (\Throwable $e) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::invalidcontent->value,
                \sprintf("%s x-fhir-query '%s' could not be resolved: %s", $kind, $name, $e->getMessage()),
            );

            return [$name, []];
        }

        $resources = $queryProvider->resourcesForQuery($resolved, $factory->fhirVersionValue());
        if ($resources === null) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf("%s x-fhir-query '%s' fetch failed; no results were populated.", $kind, $name),
            );

            return [$name, []];
        }

        return [$name, $resources];
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
            $itemContext = $this->applyVariables($item, $evalContext, $factory, $issues, $populateContext->queryProvider);

            // Resolve this item's populationContext into [name, results] — via x-fhir-query (opt-in) or FHIRPath.
            $contextName    = null;
            $contextResults = null;

            // x-fhir-query itemPopulationContext (opt-in): only entered when a query provider is configured,
            // so the FHIRPath path below is unchanged when population is offline.
            if ($populateContext->queryProvider !== null) {
                $contextExt = $this->itemPopulationContextExtension($item);
                if ($contextExt !== null) {
                    $xq = $this->xFhirQueryContextResults($contextExt, $itemContext, $factory, $issues, $populateContext->queryProvider, 'itemPopulationContext');
                    if ($xq !== null) {
                        [$contextName, $contextResults] = $xq;
                        if ($contextName === null) {
                            // Unusable x-fhir-query (missing name/expression) — build the group once, unrepeated.
                            $built = $this->buildOneItem($item, $itemContext, $populateContext, $factory, $issues);
                            if ($built !== null) {
                                $result[] = $built;
                            }

                            continue;
                        }
                    }
                }
            }

            if ($contextResults === null) {
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
            }

            if ($contextResults === []) {
                // Observable, not silent: an empty context expression omits the whole group and its
                // descendants. Without a trace this is indistinguishable from a Questionnaire with no such
                // group — mirror the empty-`initialExpression` information issue so the omission is visible.
                $issues[] = $factory->issue(
                    IssueSeverity::information->value,
                    IssueType::informationalnote->value,
                    \sprintf(
                        "itemPopulationContext '%s' (item '%s') resolved to no results; the group and its descendants were not populated.",
                        $contextName,
                        $this->primitives->stringify($item->linkId ?? null) ?? '(no linkId)',
                    ),
                );
            }

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
        $linkId = $this->primitives->stringify($item->linkId ?? null);
        if ($linkId === null) {
            // Observable, not silent: an item without a linkId cannot be represented as a QR answer, but
            // dropping it without a trace hides a malformed Questionnaire. Record it and skip only this item.
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::invalidcontent->value,
                'A Questionnaire item without a linkId was skipped; it cannot be represented in the response.',
            );

            return null;
        }

        $itemType = $this->primitives->codeOf($item->type ?? null);

        $answers    = [];
        $expression = $this->initialExpressionOf($item, $factory, $issues, $linkId);
        if ($expression === null && $this->observations->hasLinkPeriod($item)) {
            // Observation-based population is an alternative to expression-based; it applies only when the
            // item has no initialExpression. Populates from the most-recent matching supplied Observation.
            $observation = $this->observations->populate($item, $itemType, $linkId, $populateContext, $factory);
            $answers     = $observation['answers'];
            foreach ($observation['issues'] as $issue) {
                $issues[] = $issue;
            }
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

            $answerOptions = AnswerValueCoercer::answerOptionsFrom($item);

            foreach ($values as $value) {
                $coerced = $this->coercer->coerce($itemType, $value, $linkId, $factory, $issues, $answerOptions);
                if ($coerced !== null) {
                    $answers[] = $factory->answer($coerced);
                }
            }
        }

        $childItems = $this->buildItems($this->itemsOf($item), $evalContext, $populateContext, $factory, $issues);

        if ($answers === [] && $childItems === []) {
            return null;
        }

        return $factory->responseItem($linkId, $this->primitives->stringify($item->text ?? null), $answers, $childItems);
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
            // array_values guarantees the declared list<mixed> shape (toArray() is only array<int, mixed>).
            return array_values(
                $this->fhirPath
                    ->evaluate($expression, null, $evalContext, $factory->fhirVersionValue())
                    ->toArray(),
            );
        } catch (\Throwable $e) {
            // A malformed / unevaluable expression is an invalid input, not a processing fault: degrade to a
            // warning issue (code `invalid`) and an empty result so the rest of the Questionnaire still
            // populates rather than aborting the whole run.
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::invalidcontent->value,
                \sprintf('%s could not be evaluated: %s', ucfirst($label), $e->getMessage()),
            );

            return [];
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

            return $this->primitives->stringify($expressionValue->expression ?? null);
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

        $name       = $this->primitives->stringify($expressionValue->name ?? null);
        $expression = $this->primitives->stringify($expressionValue->expression ?? null);
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
        $language = $this->primitives->stringify($expressionValue->language ?? null);
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
                $code = $this->primitives->stringify($value->code ?? null);
                if ($code !== null) {
                    return $code;
                }
            }

            // id / string primitive wrapper.
            return $this->primitives->stringify($value);
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
        return $this->primitives->stringify($questionnaire->url ?? null);
    }

    /**
     * Current UTC instant as an ISO-8601 dateTime string for `QuestionnaireResponse.authored`.
     */
    private function nowInstant(): string
    {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
}

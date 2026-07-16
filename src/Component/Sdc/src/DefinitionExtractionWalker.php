<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;

/**
 * Definition-based `$extract`: walks a `QuestionnaireResponse` subtree building one resource per
 * `definitionExtract`-flagged source item, matching `Questionnaire.item.definition` element paths to
 * typed properties. Group items establish one intermediate element their children populate; leaf items
 * write answers relative to the enclosing context; `definitionExtractValue` calculated fields evaluate a
 * FHIRPath `expression` (or read a `fixed-value`) and write to an absolute path from the resource root.
 *
 * Low-level property writes delegate to the composed {@see DefinitionPathWriter} (walker orchestrates,
 * writer writes). Extracted from {@see FHIRQuestionnaireResponseExtractService}; behaviour is unchanged.
 * `collect()` mutates the caller-owned `$entries`/`$issues` accumulators by reference, matching the
 * service's transaction-assembly loop.
 *
 * @internal implementation detail of the `Sdc` extraction path; not part of the public API
 */
final class DefinitionExtractionWalker
{
    /**
     * SDC extension marking a Questionnaire item that extracts to an arbitrary resource by
     * `definition` canonical paths.
     */
    private const string DEFINITION_EXTRACT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtract';

    /**
     * SDC extension carrying a FHIRPath `expression` (or `fixed-value`) whose result is written to a
     * `definition` path.
     */
    private const string DEFINITION_EXTRACT_VALUE_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtractValue';

    /**
     * @param SafeExtensionReader         $extensionReader guarded reader for the definitionExtract(Value) extensions
     * @param DefinitionPathWriter        $writer          writes coerced values into typed model properties
     * @param QuestionnaireResponseReader $qrReader        tolerant QR structural reads (linkId / items / answers)
     * @param FHIRPathService             $fhirPath        engine evaluating `definitionExtractValue` / `fullUrl` expressions
     */
    public function __construct(
        private readonly SafeExtensionReader $extensionReader = new SafeExtensionReader(),
        private readonly DefinitionPathWriter $writer = new DefinitionPathWriter(new PropertyMetadataProvider()),
        private readonly QuestionnaireResponseReader $qrReader = new QuestionnaireResponseReader(),
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
    ) {
    }

    /**
     * Walk the response tree, building one resource per `definitionExtract`-flagged source item and
     * appending each as an `{resource, fullUrl}` entry. Recurses into non-root items to find nested roots.
     *
     * @param list<object>                                        $responseItems
     * @param array<string, object>                               $itemIndex
     * @param list<array{resource: object, fullUrl: string|null}> $entries       accumulated by reference
     * @param list<object>                                        $issues        accumulated by reference
     */
    public function collect(
        array $responseItems,
        array $itemIndex,
        ExtractModelFactory $factory,
        EvaluationContext $evalContext,
        array &$entries,
        array &$issues,
    ): void {
        foreach ($responseItems as $responseItem) {
            $linkId     = $this->qrReader->linkIdOf($responseItem);
            $sourceItem = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $canonical  = $sourceItem !== null ? $this->definitionExtractCanonical($sourceItem) : null;

            if ($canonical === null || $sourceItem === null) {
                // Not an extraction root — descend looking for nested definitionExtract roots.
                $this->collect($this->qrReader->childItems($responseItem), $itemIndex, $factory, $evalContext, $entries, $issues);
                continue;
            }

            $rootType = $this->canonicalResourceType($canonical);
            $resource = $factory->newResource($rootType);
            if ($resource === null) {
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf('definitionExtract target canonical "%s" does not resolve to a %s resource type.', $canonical, $factory->fhirVersionValue()),
                );
                continue;
            }

            // Calculated (`definitionExtractValue`) fields declared on the extraction root itself
            // (e.g. `RelatedPerson.patient.reference ← %NewPatientId`).
            $this->applyDefinitionExtractValues($sourceItem, $responseItem, $resource, $rootType, $factory, $evalContext, $issues);
            // The extraction root may itself be an answer-bearing leaf (e.g. an Observation whose own
            // `definition` is `value[x]:valueQuantity.value` carrying a decimal answer) — its answers are
            // written relative to the resource, in addition to its calculated fields and child items.
            $this->writeItemOwnAnswers($responseItem, $sourceItem, $resource, $rootType, $factory, $issues);
            $this->walkDefinitionItems($this->qrReader->childItems($responseItem), $itemIndex, $resource, [$rootType], $resource, $rootType, $factory, $evalContext, $issues);

            $fullUrl   = $this->resolveFullUrl($sourceItem, $factory, $evalContext);
            $entries[] = ['resource' => $resource, 'fullUrl' => $fullUrl];
        }
    }

    /**
     * Write an extraction root's own answers, when the root item is itself an answer-bearing leaf (its
     * `definition` addresses a property on the resource, e.g. `Observation.value[x]:valueQuantity.value`).
     * A no-op for root items that carry no `definition` of their own (a plain `Patient`/`RelatedPerson`
     * group whose fields all come from child items and calculated values).
     *
     * @param list<object> $issues accumulated by reference
     */
    private function writeItemOwnAnswers(
        object $responseItem,
        object $sourceItem,
        object $resource,
        string $rootType,
        ExtractModelFactory $factory,
        array &$issues,
    ): void {
        $segments = $this->definitionSegments($sourceItem);
        $relative = $segments !== null ? $this->relativeSegments($segments, [$rootType]) : null;
        if ($relative === null || $relative === []) {
            return;
        }

        foreach ($this->qrReader->answersOf($responseItem) as $answer) {
            try {
                $this->writer->writeLeaf($resource, $relative, $this->qrReader->answerValue($answer));
            } catch (\Throwable $e) {
                $issues[] = $factory->issue(IssueSeverity::warning->value, IssueType::processingfailure->value, $e->getMessage());
            }
        }
    }

    /**
     * Populate `$context` from the response subtree, honouring item grouping: a group item whose
     * `definition` names a complex element creates one intermediate instance that its children fill;
     * leaf items write their answers relative to the enclosing context.
     *
     * @param list<object>           $responseItems
     * @param array<string, object>  $itemIndex
     * @param non-empty-list<string> $basePath      the element path `$context` represents (e.g. ['Patient'] or ['Patient','name'])
     * @param object                 $rootResource  the resource being built (target for absolute `definitionExtractValue` paths)
     * @param string                 $rootType      the resource type name (prefix stripped from `definitionExtractValue` paths)
     * @param list<object>           $issues        accumulated by reference
     */
    private function walkDefinitionItems(
        array $responseItems,
        array $itemIndex,
        object $context,
        array $basePath,
        object $rootResource,
        string $rootType,
        ExtractModelFactory $factory,
        EvaluationContext $evalContext,
        array &$issues,
    ): void {
        foreach ($responseItems as $responseItem) {
            $linkId     = $this->qrReader->linkIdOf($responseItem);
            $sourceItem = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $segments   = $sourceItem !== null ? $this->definitionSegments($sourceItem) : null;

            // Calculated fields declared on this item are written to their absolute path from the
            // resource root, independent of the hierarchical answer-write context.
            if ($sourceItem !== null) {
                $this->applyDefinitionExtractValues($sourceItem, $responseItem, $rootResource, $rootType, $factory, $evalContext, $issues);
            }

            // No definition (logical group) — recurse in the same context.
            if ($segments === null) {
                $this->walkDefinitionItems($this->qrReader->childItems($responseItem), $itemIndex, $context, $basePath, $rootResource, $rootType, $factory, $evalContext, $issues);
                continue;
            }

            $relative = $this->relativeSegments($segments, $basePath);
            if ($relative === null) {
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf('definition path "%s" is not within the enclosing extraction context %s.', implode('.', $segments), implode('.', $basePath)),
                );
                continue;
            }

            if ($relative === []) {
                // Definition equals the context path — nothing to write here, just descend.
                $this->walkDefinitionItems($this->qrReader->childItems($responseItem), $itemIndex, $context, $basePath, $rootResource, $rootType, $factory, $evalContext, $issues);
                continue;
            }

            $answers    = $this->qrReader->answersOf($responseItem);
            $childItems = $this->qrReader->childItems($responseItem);

            if ($answers === [] && $childItems !== []) {
                // Group item: establish one intermediate element and recurse into it.
                try {
                    $child = $this->writer->createIntermediate($context, $relative);
                } catch (\RuntimeException $e) {
                    $issues[] = $factory->issue(IssueSeverity::warning->value, IssueType::processingfailure->value, $e->getMessage());
                    continue;
                }
                $this->walkDefinitionItems($childItems, $itemIndex, $child, $segments, $rootResource, $rootType, $factory, $evalContext, $issues);
                continue;
            }

            // Leaf item: write each answer relative to the current context.
            foreach ($answers as $answer) {
                try {
                    $this->writer->writeLeaf($context, $relative, $this->qrReader->answerValue($answer));
                } catch (\RuntimeException $e) {
                    $issues[] = $factory->issue(IssueSeverity::warning->value, IssueType::processingfailure->value, $e->getMessage());
                }
            }
            if ($childItems !== []) {
                $this->walkDefinitionItems($childItems, $itemIndex, $context, $basePath, $rootResource, $rootType, $factory, $evalContext, $issues);
            }
        }
    }

    /**
     * Read a Questionnaire item's `definitionExtract` target canonical (the `definition` sub-extension),
     * or null when the item is not an extraction root.
     */
    private function definitionExtractCanonical(object $item): ?string
    {
        foreach ($this->extensionReader->readSubExtensions($item) as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::DEFINITION_EXTRACT_URL) {
                continue;
            }
            $definition = $this->extensionReader->findExtension($extension, 'definition');
            if ($definition === null) {
                return null;
            }

            return $this->qrReader->stringifyPrimitive($this->extensionReader->readValue($definition));
        }

        return null;
    }

    /**
     * The bare resource type from a canonical: the last path segment before any `#fragment`/`|version`.
     */
    private function canonicalResourceType(string $canonical): string
    {
        $base  = strtok($canonical, '#|');
        $base  = $base === false ? $canonical : $base;
        $slash = strrpos($base, '/');

        return $slash === false ? $base : substr($base, $slash + 1);
    }

    /**
     * Split an item's `definition` element path into segments (`…Patient#Patient.name.given` → the
     * `#`-fragment `Patient.name.given` → `['Patient','name','given']`), or null when absent.
     *
     * @return non-empty-list<string>|null
     */
    private function definitionSegments(object $item): ?array
    {
        $raw = property_exists($item, 'definition') ? $this->qrReader->stringifyPrimitive($item->definition ?? null) : null;

        return $raw === null ? null : $this->segmentsFromDefinition($raw);
    }

    /**
     * Split a raw `definition` string into element-path segments (`…Patient#Patient.name.given` → the
     * `#`-fragment → `['Patient','name','given']`), or null when empty.
     *
     * @return non-empty-list<string>|null
     */
    private function segmentsFromDefinition(string $raw): ?array
    {
        if ($raw === '') {
            return null;
        }

        $hash     = strpos($raw, '#');
        $fragment = $hash === false ? $raw : substr($raw, $hash + 1);
        $segments = array_values(array_filter(explode('.', $fragment), static fn (string $s): bool => $s !== ''));

        return $segments === [] ? null : $segments;
    }

    /**
     * Resolve an extraction root's `fullUrl` sub-expression (evaluated against the QR root) to a string,
     * or null when absent/unreadable/malformed (the caller then mints a fresh `urn:uuid`).
     */
    private function resolveFullUrl(object $item, ExtractModelFactory $factory, EvaluationContext $evalContext): ?string
    {
        foreach ($this->extensionReader->readSubExtensions($item) as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::DEFINITION_EXTRACT_URL) {
                continue;
            }
            $fullUrl = $this->extensionReader->findExtension($extension, 'fullUrl');
            if ($fullUrl === null) {
                return null;
            }
            $expression = $this->qrReader->stringifyPrimitive($this->extensionReader->readValue($fullUrl));
            if ($expression === null) {
                return null;
            }

            try {
                // A `fullUrl` sub-expression is resolved against the QR root (its historical focus),
                // taken from the stable resourceNode handle rather than the evaluator-mutated root.
                return $this->qrReader->stringifyPrimitive($this->evaluateToScalar($expression, $evalContext->getResourceNode(), $factory, $evalContext));
            } catch (\Throwable) {
                // A malformed fullUrl expression falls back to a freshly-minted urn:uuid.
                return null;
            }
        }

        return null;
    }

    /**
     * Apply every `definitionExtractValue` on a source item: evaluate its FHIRPath `expression` and
     * write the result to its `definition` path, taken as absolute from the resource root. A raw scalar
     * result is coerced by {@see DefinitionPathWriter} into the target property's primitive wrapper
     * (e.g. a computed uri into a `?UriPrimitive`); a failed expression surfaces a diagnostic issue.
     *
     * Each `definitionExtractValue` FHIRPath `expression` is evaluated with `$responseItem` (the QR
     * response item carrying the extension) as focus, so relative navigation such as
     * `item.where(linkId=…)` resolves against that item's children while `%resource` stays the QR root.
     *
     * @param list<object> $issues accumulated by reference
     */
    private function applyDefinitionExtractValues(
        object $item,
        object $responseItem,
        object $rootResource,
        string $rootType,
        ExtractModelFactory $factory,
        EvaluationContext $evalContext,
        array &$issues,
    ): void {
        foreach ($this->extensionReader->readSubExtensions($item) as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::DEFINITION_EXTRACT_VALUE_URL) {
                continue;
            }

            // A `definitionExtractValue` sources its value from EITHER a FHIRPath `expression` (evaluated
            // against the QR) OR a `fixed-value` sub-extension carrying a literal (a `code`, `uri`,
            // `Coding`, `CodeableConcept`, …). Both target the same absolute `definition` path.
            $definitionExt = $this->extensionReader->findExtension($extension, 'definition');
            $expressionExt = $this->extensionReader->findExtension($extension, 'expression');
            $fixedExt      = $this->extensionReader->findExtension($extension, 'fixed-value');
            if ($definitionExt === null || ($expressionExt === null && $fixedExt === null)) {
                continue;
            }

            $definition = $this->qrReader->stringifyPrimitive($this->extensionReader->readValue($definitionExt));
            if ($definition === null) {
                continue;
            }

            $segments = $this->segmentsFromDefinition($definition);
            $relative = $segments !== null ? $this->relativeSegments($segments, [$rootType]) : null;
            if ($relative === null || $relative === []) {
                $issues[] = $factory->issue(
                    IssueSeverity::warning->value,
                    IssueType::processingfailure->value,
                    \sprintf('definitionExtractValue path "%s" is not within the resource type %s.', $definition, $rootType),
                );
                continue;
            }

            if ($expressionExt !== null) {
                $expression = $this->qrReader->expressionString($this->extensionReader->readValue($expressionExt));
                if ($expression === null) {
                    continue;
                }
                try {
                    $value = $this->evaluateToScalar($expression, $responseItem, $factory, $evalContext);
                } catch (\Throwable $e) {
                    $issues[] = $factory->issue(
                        IssueSeverity::warning->value,
                        IssueType::processingfailure->value,
                        \sprintf('definitionExtractValue expression "%s" failed to evaluate: %s', $expression, $e->getMessage()),
                    );
                    continue;
                }
            } else {
                // $fixedExt is non-null here (guaranteed by the guard above).
                $value = $this->extensionReader->readValue($fixedExt);
            }

            if ($value === null) {
                // An empty result set / absent fixed value is not an error — nothing to write.
                continue;
            }

            try {
                // The writer coerces a raw string scalar into the target property's primitive wrapper.
                // Catch \Throwable (not just \RuntimeException): a calculated value whose type does not
                // match a strictly-typed leaf (e.g. a computed number for a string-typed primitive) would
                // otherwise raise a \TypeError and abort the whole extraction — surface it as an issue.
                $this->writer->writeLeaf($rootResource, $relative, $value);
            } catch (\Throwable $e) {
                $issues[] = $factory->issue(IssueSeverity::warning->value, IssueType::processingfailure->value, $e->getMessage());
            }
        }
    }

    /**
     * Evaluate a FHIRPath expression with `$focus` as the evaluation focus (`%context`/`$this`), the
     * run's context supplying the allocated-id external constants and the QR-root `%resource` binding.
     * Returns its first result item, or null when the result collection is empty.
     *
     * @throws \Throwable when the expression cannot be parsed or evaluated
     */
    private function evaluateToScalar(string $expression, mixed $focus, ExtractModelFactory $factory, EvaluationContext $evalContext): mixed
    {
        return $this->fhirPath->evaluate(
            $expression,
            $focus,
            $evalContext,
            $factory->fhirVersionValue(),
        )->first();
    }

    /**
     * The portion of `$segments` beneath `$basePath` (which must be a prefix), or null when `$segments`
     * does not sit within `$basePath` (e.g. a cross-resource path — out of scope for single-resource M02).
     *
     * @param non-empty-list<string> $segments
     * @param non-empty-list<string> $basePath
     *
     * @return list<string>|null
     */
    private function relativeSegments(array $segments, array $basePath): ?array
    {
        if (array_slice($segments, 0, count($basePath)) !== $basePath) {
            return null;
        }

        return array_slice($segments, count($basePath));
    }
}

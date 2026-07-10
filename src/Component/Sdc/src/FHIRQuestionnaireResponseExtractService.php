<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\BundleType;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\HTTPVerb;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\ObservationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HTTPVerbType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntryRequest;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcome\OperationOutcomeIssue;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcomeResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;

/**
 * R4 observation- and definition-based `QuestionnaireResponse/$extract`.
 *
 * For each response item whose **source Questionnaire item** carries the SDC `observationExtract`
 * flag and an `item.code`, this builds one `Observation` per answer and assembles them into a
 * transaction `Bundle` (always a Bundle — even for a single Observation — per the SDC extraction
 * spec). Each Observation copies its `code` from the Questionnaire item, its `value[x]` from the
 * answer, and its `subject`/`effectiveDateTime`/`performer` from the response's
 * `subject`/`authored`/`author`, and links back to the response via `derivedFrom`.
 *
 * Definition-based extraction builds an arbitrary resource per `definitionExtract`-flagged item by
 * matching `Questionnaire.item.definition` canonical paths to typed properties via
 * {@see DefinitionPathWriter}, honouring item grouping so a group item establishes one intermediate
 * element that its children populate.
 *
 * Extension reads route exclusively through {@see SafeExtensionReader} so that constructor-bypassed
 * (deserializer-origin) objects with uninitialized typed properties degrade to "absent" rather than
 * throwing — the model-initialization footgun.
 *
 * `extractAllocateId` allocates a `urn:uuid:` per declared variable; a `definitionExtract`'s `fullUrl`
 * sub-expression resolves that variable into an entry's `fullUrl`, and a `definitionExtractValue` whose
 * FHIRPath references the same variable writes it into a cross-resource `reference` — so two extracted
 * resources point at each other via matching `urn:uuid:`.
 *
 * A `definitionExtractValue` evaluates a FHIRPath expression and writes the result to its `definition`
 * path; a raw scalar result is coerced by {@see DefinitionPathWriter} into the target property's
 * declared primitive wrapper, and a malformed expression surfaces a diagnostic issue.
 *
 * Each entry's `request` is `POST Type` for a resource with no logical `id` and `PUT Type/id` for one
 * whose `id` was written during extraction (a hidden item or `definitionExtractValue` targeting `.id`).
 *
 * Out of scope here (later M03 / M04): template-based extraction, provenance, and R4B/R5 parity.
 * Definition extraction is R4-only.
 */
final class FHIRQuestionnaireResponseExtractService implements ExtractServiceInterface
{
    /**
     * SDC flag extension marking a Questionnaire item whose answers extract to an Observation.
     *
     * @see https://build.fhir.org/ig/HL7/sdc/en/extraction.html#observation-based-extraction
     */
    private const string OBSERVATION_EXTRACT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-observationExtract';

    /**
     * SDC extension marking a Questionnaire item that extracts to an arbitrary resource by
     * `definition` canonical paths.
     *
     * @see https://build.fhir.org/ig/HL7/sdc/en/extraction.html#definition-based-extraction
     */
    private const string DEFINITION_EXTRACT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtract';

    /**
     * SDC extension carrying a FHIRPath `expression` whose result is written to a `definition` path —
     * used here to write an allocated `urn:uuid:` into a cross-resource `reference`.
     *
     * @see https://build.fhir.org/ig/HL7/sdc/en/extraction.html#defining-values
     */
    private const string DEFINITION_EXTRACT_VALUE_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-definitionExtractValue';

    /**
     * SDC extension declaring a variable that receives a freshly-allocated id (a `urn:uuid:`), usable
     * from `fullUrl` and `definitionExtractValue` FHIRPath expressions as an external constant (`%name`).
     *
     * @see https://build.fhir.org/ig/HL7/sdc/en/extraction.html#allocating-ids
     */
    private const string EXTRACT_ALLOCATE_ID_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-extractAllocateId';

    public function __construct(
        private readonly SafeExtensionReader $extensionReader = new SafeExtensionReader(),
        private readonly DefinitionPathWriter $writer = new DefinitionPathWriter(new PropertyMetadataProvider()),
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
    ) {
    }

    public function extract(object $questionnaireResponse, ExtractContext $context): ExtractResult
    {
        if (!$questionnaireResponse instanceof QuestionnaireResponseResource) {
            throw new \InvalidArgumentException(\sprintf('%s extracts R4 QuestionnaireResponse only; got %s', self::class, $questionnaireResponse::class));
        }

        $questionnaire = $context->questionnaire;
        $itemIndex     = $questionnaire instanceof QuestionnaireResource
            ? $this->indexQuestionnaireItems($questionnaire->item)
            : [];

        /** @var list<OperationOutcomeIssue> $issues */
        $issues = [];

        // Allocate one urn:uuid per declared `extractAllocateId` variable and expose them to every
        // FHIRPath expression in this run as external constants (`%name`). Scope is treated as global
        // (names are unique across the corpus); item-scoped shadowing is not modelled.
        $allocatedIds = $this->collectAllocatedIds($questionnaire);
        $evalContext  = new EvaluationContext(
            rootResource: $questionnaireResponse,
            externalConstants: $allocatedIds,
        );

        $observations = [];
        $this->collectObservations($questionnaireResponse->item, $itemIndex, $questionnaireResponse, $observations);

        /** @var list<array{resource: AbstractResource, fullUrl: string|null}> $entries */
        $entries = [];
        foreach ($observations as $observation) {
            $entries[] = ['resource' => $observation, 'fullUrl' => null];
        }
        $this->collectDefinitionResources($questionnaireResponse->item, $itemIndex, $evalContext, $entries, $issues);

        $bundle  = $this->assembleTransactionBundle($entries);
        $outcome = $this->buildOutcome($entries, $issues);

        return new ExtractResult($bundle, $outcome);
    }

    /**
     * Index every Questionnaire item (recursively) by its linkId.
     *
     * @param array<int, QuestionnaireItem> $items
     *
     * @return array<string, QuestionnaireItem>
     */
    private function indexQuestionnaireItems(array $items): array
    {
        $index = [];
        foreach ($items as $item) {
            $linkId = $this->linkIdString($item->linkId);
            if ($linkId !== null) {
                $index[$linkId] = $item;
            }
            $index += $this->indexQuestionnaireItems($item->item);
        }

        return $index;
    }

    /**
     * Walk the response item tree, appending an Observation for each answer under an extract-flagged item.
     *
     * @param array<int, QuestionnaireResponseItem> $responseItems
     * @param array<string, QuestionnaireItem>      $itemIndex
     * @param list<ObservationResource>             $observations  accumulated by reference
     */
    private function collectObservations(
        array $responseItems,
        array $itemIndex,
        QuestionnaireResponseResource $response,
        array &$observations,
    ): void {
        foreach ($responseItems as $responseItem) {
            $linkId       = $this->linkIdString($responseItem->linkId);
            $sourceItem   = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $extractsHere = $sourceItem !== null
                && $this->hasObservationExtract($sourceItem)
                && $sourceItem->code !== [];

            if ($extractsHere) {
                // $sourceItem is non-null here — implied by $extractsHere.
                $code = new CodeableConcept(coding: $sourceItem->code);
                foreach ($responseItem->answer as $answer) {
                    $observations[] = $this->buildObservation($code, $answer->value, $response);
                    // Nested answer.item may itself carry extract-flagged questions.
                    $this->collectObservations($answer->item, $itemIndex, $response, $observations);
                }
            } else {
                foreach ($responseItem->answer as $answer) {
                    $this->collectObservations($answer->item, $itemIndex, $response, $observations);
                }
            }

            $this->collectObservations($responseItem->item, $itemIndex, $response, $observations);
        }
    }

    /**
     * True when the Questionnaire item carries a truthy `observationExtract` flag extension.
     */
    private function hasObservationExtract(QuestionnaireItem $item): bool
    {
        foreach ($item->extension as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::OBSERVATION_EXTRACT_URL) {
                continue;
            }

            $value = $this->extensionReader->readValue($extension);

            // `observationExtract` is a boolean flag; a present extension with a non-false value extracts.
            return $value !== false;
        }

        return false;
    }

    /**
     * Walk the response tree, building one resource per `definitionExtract`-flagged source item.
     *
     * @param array<int, QuestionnaireResponseItem>                         $responseItems
     * @param array<string, QuestionnaireItem>                              $itemIndex
     * @param list<array{resource: AbstractResource, fullUrl: string|null}> $entries       accumulated by reference
     * @param list<OperationOutcomeIssue>                                   $issues        accumulated by reference
     */
    private function collectDefinitionResources(
        array $responseItems,
        array $itemIndex,
        EvaluationContext $evalContext,
        array &$entries,
        array &$issues,
    ): void {
        foreach ($responseItems as $responseItem) {
            $linkId     = $this->linkIdString($responseItem->linkId);
            $sourceItem = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $canonical  = $sourceItem !== null ? $this->definitionExtractCanonical($sourceItem) : null;

            if ($canonical === null || $sourceItem === null) {
                // Not an extraction root — descend looking for nested definitionExtract roots.
                $this->collectDefinitionResources($responseItem->item, $itemIndex, $evalContext, $entries, $issues);
                continue;
            }

            $class = $this->resolveResourceClass($canonical);
            if ($class === null) {
                $issues[] = $this->issue(
                    IssueType::processingfailure,
                    \sprintf('definitionExtract target canonical "%s" does not resolve to an R4 resource type.', $canonical),
                );
                continue;
            }

            $resource = new $class();
            $rootType = $this->canonicalResourceType($canonical);

            // Calculated (`definitionExtractValue`) fields declared on the extraction root itself
            // (e.g. `RelatedPerson.patient.reference ← %NewPatientId`).
            $this->applyDefinitionExtractValues($sourceItem, $resource, $rootType, $evalContext, $issues);
            $this->walkDefinitionItems($responseItem->item, $itemIndex, $resource, [$rootType], $resource, $rootType, $evalContext, $issues);

            $fullUrl   = $this->resolveFullUrl($sourceItem, $evalContext);
            $entries[] = ['resource' => $resource, 'fullUrl' => $fullUrl];
        }
    }

    /**
     * Populate `$context` from the response subtree, honouring item grouping: a group item whose
     * `definition` names a complex element creates one intermediate instance that its children fill;
     * leaf items write their answers relative to the enclosing context.
     *
     * @param array<int, QuestionnaireResponseItem> $responseItems
     * @param array<string, QuestionnaireItem>      $itemIndex
     * @param non-empty-list<string>                $basePath      the element path `$context` represents (e.g. ['Patient'] or ['Patient','name'])
     * @param object                                $rootResource  the resource being built (target for absolute `definitionExtractValue` paths)
     * @param string                                $rootType      the resource type name (prefix stripped from `definitionExtractValue` paths)
     * @param list<OperationOutcomeIssue>           $issues        accumulated by reference
     */
    private function walkDefinitionItems(
        array $responseItems,
        array $itemIndex,
        object $context,
        array $basePath,
        object $rootResource,
        string $rootType,
        EvaluationContext $evalContext,
        array &$issues,
    ): void {
        foreach ($responseItems as $responseItem) {
            $linkId     = $this->linkIdString($responseItem->linkId);
            $sourceItem = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $segments   = $sourceItem !== null ? $this->definitionSegments($sourceItem->definition) : null;

            // Calculated fields declared on this item are written to their absolute path from the
            // resource root, independent of the hierarchical answer-write context.
            if ($sourceItem !== null) {
                $this->applyDefinitionExtractValues($sourceItem, $rootResource, $rootType, $evalContext, $issues);
            }

            // No definition (logical group) — recurse in the same context.
            if ($segments === null) {
                $this->walkDefinitionItems($responseItem->item, $itemIndex, $context, $basePath, $rootResource, $rootType, $evalContext, $issues);
                continue;
            }

            $relative = $this->relativeSegments($segments, $basePath);
            if ($relative === null) {
                $issues[] = $this->issue(
                    IssueType::processingfailure,
                    \sprintf('definition path "%s" is not within the enclosing extraction context %s.', implode('.', $segments), implode('.', $basePath)),
                );
                continue;
            }

            if ($relative === []) {
                // Definition equals the context path — nothing to write here, just descend.
                $this->walkDefinitionItems($responseItem->item, $itemIndex, $context, $basePath, $rootResource, $rootType, $evalContext, $issues);
                continue;
            }

            $hasAnswers = $responseItem->answer !== [];

            if (!$hasAnswers && $responseItem->item !== []) {
                // Group item: establish one intermediate element and recurse into it.
                try {
                    $child = $this->writer->createIntermediate($context, $relative);
                } catch (\RuntimeException $e) {
                    $issues[] = $this->issue(IssueType::processingfailure, $e->getMessage());
                    continue;
                }
                $this->walkDefinitionItems($responseItem->item, $itemIndex, $child, $segments, $rootResource, $rootType, $evalContext, $issues);
                continue;
            }

            // Leaf item: write each answer relative to the current context.
            foreach ($responseItem->answer as $answer) {
                try {
                    $this->writer->writeLeaf($context, $relative, $answer->value);
                } catch (\RuntimeException $e) {
                    $issues[] = $this->issue(IssueType::processingfailure, $e->getMessage());
                }
            }
            if ($responseItem->item !== []) {
                $this->walkDefinitionItems($responseItem->item, $itemIndex, $context, $basePath, $rootResource, $rootType, $evalContext, $issues);
            }
        }
    }

    /**
     * Read a Questionnaire item's `definitionExtract` target canonical (the `definition` sub-extension),
     * or null when the item is not an extraction root.
     */
    private function definitionExtractCanonical(QuestionnaireItem $item): ?string
    {
        foreach ($item->extension as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::DEFINITION_EXTRACT_URL) {
                continue;
            }
            $definition = $this->extensionReader->findExtension($extension, 'definition');
            if ($definition === null) {
                return null;
            }

            return $this->stringifyPrimitive($this->extensionReader->readValue($definition));
        }

        return null;
    }

    /**
     * Resolve a base-type `StructureDefinition/{Type}` canonical to its generated R4 resource class,
     * or null when no such class exists (profiles / unknown types are unsupported here — M02 stub).
     *
     * @return class-string<AbstractResource>|null
     */
    private function resolveResourceClass(string $canonical): ?string
    {
        $type = $this->canonicalResourceType($canonical);
        $fqcn = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\' . $type . 'Resource';

        if (!class_exists($fqcn) || !is_subclass_of($fqcn, AbstractResource::class)) {
            return null;
        }

        return $fqcn;
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
     * Split an item `definition`'s element path into segments (`…Patient#Patient.name.given` → the
     * `#`-fragment `Patient.name.given` → `['Patient','name','given']`), or null when absent.
     *
     * @return non-empty-list<string>|null
     */
    private function definitionSegments(?UriPrimitive $definition): ?array
    {
        $raw = $definition instanceof UriPrimitive ? ($definition->value ?? null) : null;

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
     * Allocate one `urn:uuid:` per `extractAllocateId`-declared variable across the Questionnaire
     * (root extensions + every item, recursively), keyed by the variable name (without the `%`).
     *
     * @return array<string, string> variable name => allocated `urn:uuid:` value
     */
    private function collectAllocatedIds(?object $questionnaire): array
    {
        $ids = [];
        if (!$questionnaire instanceof QuestionnaireResource) {
            return $ids;
        }

        // Root extensions/items are read tolerantly: a constructor-bypassed deserializer object leaves
        // untouched typed properties uninitialized (the model-init footgun), so direct access throws.
        $this->collectAllocateNames($this->extensionReader->readSubExtensions($questionnaire), $ids);
        $this->collectAllocateNamesFromItems($this->safeChildItems($questionnaire), $ids);

        return $ids;
    }

    /**
     * @param list<object>          $extensions
     * @param array<string, string> $ids        accumulated by reference
     */
    private function collectAllocateNames(array $extensions, array &$ids): void
    {
        foreach ($extensions as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::EXTRACT_ALLOCATE_ID_URL) {
                continue;
            }
            $name = $this->stringifyPrimitive($this->extensionReader->readValue($extension));
            if ($name !== null && !isset($ids[$name])) {
                $ids[$name] = $this->uuidUrn();
            }
        }
    }

    /**
     * @param array<int, QuestionnaireItem> $items
     * @param array<string, string>         $ids   accumulated by reference
     */
    private function collectAllocateNamesFromItems(array $items, array &$ids): void
    {
        foreach ($items as $item) {
            $this->collectAllocateNames($this->extensionReader->readSubExtensions($item), $ids);
            $this->collectAllocateNamesFromItems($this->safeChildItems($item), $ids);
        }
    }

    /**
     * Read a model object's `item` array tolerantly — a constructor-bypassed deserializer object leaves
     * an untouched `item` property uninitialized, so direct access throws (the model-init footgun).
     *
     * @return list<QuestionnaireItem>
     */
    private function safeChildItems(object $object): array
    {
        // `??` uses isset() semantics, reading an uninitialized typed property as "absent" not \Error.
        $items = property_exists($object, 'item') ? ($object->item ?? []) : [];
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, static fn (mixed $v): bool => $v instanceof QuestionnaireItem));
    }

    /**
     * Resolve an extraction root's `fullUrl` sub-expression (a FHIRPath string on the `definitionExtract`
     * extension) to the entry `fullUrl`, or null when absent — leaving a fresh `urn:uuid:` to be minted.
     */
    private function resolveFullUrl(QuestionnaireItem $item, EvaluationContext $evalContext): ?string
    {
        foreach ($this->extensionReader->readSubExtensions($item) as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::DEFINITION_EXTRACT_URL) {
                continue;
            }
            $fullUrl = $this->extensionReader->findExtension($extension, 'fullUrl');
            if ($fullUrl === null) {
                return null;
            }
            $expression = $this->stringifyPrimitive($this->extensionReader->readValue($fullUrl));
            if ($expression === null) {
                return null;
            }

            try {
                return $this->stringifyPrimitive($this->evaluateToScalar($expression, $evalContext));
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
     * @param list<OperationOutcomeIssue> $issues accumulated by reference
     */
    private function applyDefinitionExtractValues(
        QuestionnaireItem $item,
        object $rootResource,
        string $rootType,
        EvaluationContext $evalContext,
        array &$issues,
    ): void {
        foreach ($this->extensionReader->readSubExtensions($item) as $extension) {
            if ($this->extensionReader->readUrl($extension) !== self::DEFINITION_EXTRACT_VALUE_URL) {
                continue;
            }

            $definitionExt = $this->extensionReader->findExtension($extension, 'definition');
            $expressionExt = $this->extensionReader->findExtension($extension, 'expression');
            if ($definitionExt === null || $expressionExt === null) {
                continue;
            }

            $definition = $this->stringifyPrimitive($this->extensionReader->readValue($definitionExt));
            $expression = $this->expressionString($this->extensionReader->readValue($expressionExt));
            if ($definition === null || $expression === null) {
                continue;
            }

            $segments = $this->segmentsFromDefinition($definition);
            $relative = $segments !== null ? $this->relativeSegments($segments, [$rootType]) : null;
            if ($relative === null || $relative === []) {
                $issues[] = $this->issue(
                    IssueType::processingfailure,
                    \sprintf('definitionExtractValue path "%s" is not within the resource type %s.', $definition, $rootType),
                );
                continue;
            }

            try {
                $value = $this->evaluateToScalar($expression, $evalContext);
            } catch (\Throwable $e) {
                $issues[] = $this->issue(
                    IssueType::processingfailure,
                    \sprintf('definitionExtractValue expression "%s" failed to evaluate: %s', $expression, $e->getMessage()),
                );
                continue;
            }

            if ($value === null) {
                // An empty result set is not an error — nothing to write for this field.
                continue;
            }

            try {
                // The writer coerces a raw string scalar into the target property's primitive wrapper.
                // Catch \Throwable (not just \RuntimeException): a calculated value whose type does not
                // match a strictly-typed leaf (e.g. a computed number for a string-typed primitive) would
                // otherwise raise a \TypeError and abort the whole extraction — surface it as an issue.
                $this->writer->writeLeaf($rootResource, $relative, $value);
            } catch (\Throwable $e) {
                $issues[] = $this->issue(IssueType::processingfailure, $e->getMessage());
            }
        }
    }

    /**
     * Evaluate a FHIRPath expression against the run's context (QR root + allocated-id external
     * constants) and return its first result item, or null when the result collection is empty.
     *
     * Propagates evaluation failures so callers can surface a diagnostic issue for a malformed
     * expression rather than silently dropping the calculated value.
     *
     * @throws \Throwable when the expression cannot be parsed or evaluated
     */
    private function evaluateToScalar(string $expression, EvaluationContext $evalContext): mixed
    {
        return $this->fhirPath->evaluate(
            $expression,
            $evalContext->getRootResource(),
            $evalContext,
            FhirVersion::R4->value,
        )->first();
    }

    /**
     * Extract the FHIRPath string from a `valueExpression` (an `Expression` datatype), tolerating a
     * constructor-bypassed object, or null when unreadable.
     */
    private function expressionString(mixed $value): ?string
    {
        if (is_object($value) && property_exists($value, 'expression')) {
            return $this->stringifyPrimitive($value->expression ?? null);
        }

        return null;
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

    /**
     * Coerce a primitive-wrapper-or-string value to a plain string, or null when unreadable.
     */
    private function stringifyPrimitive(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value === '' ? null : $value;
        }
        if (is_object($value) && property_exists($value, 'value')) {
            $inner = $value->value ?? null;

            return is_string($inner) && $inner !== '' ? $inner : null;
        }

        return null;
    }

    private function issue(IssueType $code, string $diagnostics): OperationOutcomeIssue
    {
        return new OperationOutcomeIssue(
            severity: new IssueSeverityType(IssueSeverity::warning->value),
            code: new IssueTypeType($code->value),
            diagnostics: $diagnostics,
        );
    }

    private function buildObservation(
        CodeableConcept $code,
        mixed $answerValue,
        QuestionnaireResponseResource $response,
    ): ObservationResource {
        $author   = $response->author   ?? null;
        $qrId     = $response->id       ?? null;
        $authored = $response->authored ?? null;

        return new ObservationResource(
            status: new ObservationStatusType(ObservationStatus::final->value),
            code: $code,
            subject: $response->subject ?? null,
            // Per SDC observation-based extraction, both effectiveDateTime and issued map from QR.authored.
            effective: $authored,
            issued: $this->authoredAsInstant($authored),
            performer: $author instanceof Reference ? [$author] : [],
            value: $this->mapAnswerToObservationValue($answerValue),
            derivedFrom: $qrId !== null
                ? [new Reference(reference: 'QuestionnaireResponse/' . $qrId)]
                : [],
        );
    }

    /**
     * Convert QR.authored (a `dateTime`) to an `instant` for `Observation.issued`, per the SDC mapping.
     *
     * Returns null when authored is absent or too low-precision to be a valid instant (which requires
     * full date+time+timezone).
     */
    private function authoredAsInstant(?DateTimePrimitive $authored): ?InstantPrimitive
    {
        if ($authored === null) {
            return null;
        }

        $raw = (string) $authored;
        if ($raw === '') {
            return null;
        }

        try {
            return new InstantPrimitive(value: FHIRInstant::parse($raw));
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Map a QuestionnaireResponse answer value onto an `Observation.value[x]` variant.
     *
     * `Coding` answers become a single-coding `CodeableConcept`; types the choice does not accept
     * (e.g. `date`, `uri`, `Attachment`, `Reference`) yield null (the Observation carries no value).
     */
    private function mapAnswerToObservationValue(
        mixed $answerValue,
    ): Quantity|CodeableConcept|StringPrimitive|DateTimePrimitive|TimePrimitive|string|bool|int|null {
        if ($answerValue instanceof Coding) {
            return new CodeableConcept(coding: [$answerValue]);
        }

        if (
            is_bool($answerValue)
            || is_int($answerValue)
            || is_string($answerValue)
            || $answerValue instanceof Quantity
            || $answerValue instanceof CodeableConcept
            || $answerValue instanceof StringPrimitive
            || $answerValue instanceof DateTimePrimitive
            || $answerValue instanceof TimePrimitive
        ) {
            return $answerValue;
        }

        return null;
    }

    /**
     * Assemble the extracted resources into a transaction Bundle. Per SDC definition-based extraction,
     * an entry's `request` directive is derived from whether the resource carries a logical `id`:
     * no id → `POST` (create) to the resource type (`Patient`); an id → `PUT` (update) to `Type/id`
     * (`Patient/123`). An id is present when a hidden item or `definitionExtractValue` wrote `Resource.id`.
     * Each entry's `fullUrl` is the resource's allocated `urn:uuid:` (when a `fullUrl` expression resolved
     * one) or a freshly-minted `urn:uuid:` otherwise — the spec sets `fullUrl` from the expression
     * regardless of create-vs-update.
     *
     * @see https://build.fhir.org/ig/HL7/sdc/en/extraction.html — "if the resource has no id property
     *      set the value to POST … otherwise set the value to PUT".
     *
     * @param list<array{resource: AbstractResource, fullUrl: string|null}> $entries
     */
    private function assembleTransactionBundle(array $entries): BundleResource
    {
        $bundleEntries = [];
        foreach ($entries as $entry) {
            $resource  = $entry['resource'];
            $type      = $this->resourceTypeOf($resource);
            $logicalId = $this->logicalIdOf($resource);

            [$method, $url] = $logicalId === null
                ? [HTTPVerb::post, $type]
                : [HTTPVerb::put, $type . '/' . $logicalId];

            $bundleEntries[] = new BundleEntry(
                fullUrl: new UriPrimitive(value: $entry['fullUrl'] ?? $this->uuidUrn()),
                resource: $resource,
                request: new BundleEntryRequest(
                    method: new HTTPVerbType($method->value),
                    url: new UriPrimitive(value: $url),
                ),
            );
        }

        return new BundleResource(
            type: new BundleTypeType(BundleType::transaction->value),
            entry: $bundleEntries,
        );
    }

    /**
     * The extracted resource's logical `id` when one was written during extraction (a hidden item or a
     * `definitionExtractValue` targeting `.id`), or null when absent/blank — which keeps the entry a
     * create (`POST`) rather than an update (`PUT`).
     */
    private function logicalIdOf(object $resource): ?string
    {
        // `??` uses isset() semantics so an uninitialized typed `id` (deserializer-origin) reads as
        // absent rather than throwing (the model-init footgun); our extracted resources default it to null.
        $id = property_exists($resource, 'id') ? ($resource->id ?? null) : null;

        return is_string($id) && $id !== '' ? $id : null;
    }

    /**
     * Build the companion `OperationOutcome`: any collected `$issues`, plus an informational note when
     * nothing was extracted. Returns null when there is nothing to report.
     *
     * @param list<array{resource: AbstractResource, fullUrl: string|null}> $entries
     * @param list<OperationOutcomeIssue>                                   $issues
     */
    private function buildOutcome(array $entries, array $issues): ?OperationOutcomeResource
    {
        if ($entries === []) {
            $issues[] = new OperationOutcomeIssue(
                severity: new IssueSeverityType(IssueSeverity::information->value),
                code: new IssueTypeType(IssueType::informationalnote->value),
                diagnostics: 'No resources were extracted from the QuestionnaireResponse.',
            );
        }

        return $issues === [] ? null : new OperationOutcomeResource(issue: $issues);
    }

    /**
     * The FHIR resource type name for a generated resource (e.g. `PatientResource` → `Patient`).
     */
    private function resourceTypeOf(object $resource): string
    {
        $short = (new \ReflectionClass($resource))->getShortName();

        return str_ends_with($short, 'Resource') ? substr($short, 0, -8) : $short;
    }

    /**
     * Normalise a `linkId` (which may be a StringPrimitive wrapper or a raw string) to a plain string.
     */
    private function linkIdString(StringPrimitive|string|null $linkId): ?string
    {
        if ($linkId === null) {
            return null;
        }
        if (is_string($linkId)) {
            return $linkId === '' ? null : $linkId;
        }

        $value = $linkId->value ?? null;

        return $value === null || $value === '' ? null : $value;
    }

    /**
     * Generate an RFC 4122 v4 `urn:uuid:` for a Bundle entry fullUrl.
     */
    private function uuidUrn(): string
    {
        $bytes    = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0F) | 0x40); // version 4
        $bytes[8] = chr((ord($bytes[8]) & 0x3F) | 0x80); // variant 10
        $hex      = bin2hex($bytes);

        return \sprintf(
            'urn:uuid:%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        );
    }
}

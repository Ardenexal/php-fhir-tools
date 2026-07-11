<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\HTTPVerb;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\ObservationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;

/**
 * Observation- and definition-based `QuestionnaireResponse/$extract` across R4/R4B/R5.
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
 * **Version handling.** The produced Bundle/OperationOutcome/target-resource objects are constructed
 * from the model namespace named by {@see ExtractContext::$fhirVersion} through {@see ExtractModelFactory},
 * and inputs are read tolerantly (property-existence / {@see SafeExtensionReader}) so the structural walk
 * is version-agnostic. The response's own model version MUST match the requested version, though — the
 * extracted values are version-specific model objects that cannot be grafted across versions — so a
 * mismatched response is refused with a diagnostic issue and an empty Bundle. **Definition-based
 * extraction is version-generic (R4/R4B/R5); observation-based extraction remains R4-only** — its
 * `Observation` assembly builds R4 datatypes, so a non-R4 response carrying `observationExtract` items
 * yields a diagnostic issue rather than a wrong-version Observation.
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
 * Template-based extraction (`templateExtract`) is delegated to {@see TemplateExtractor}: contained
 * template resources are cloned and populated via `templateExtractContext`/`templateExtractValue`
 * FHIRPath expressions and merged into the same transaction Bundle. Out of scope (M04 / backlog):
 * StructureMap-based extraction and provenance.
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

    /**
     * `Provenance.agent.who.display` for the emitted extraction Provenance. The toolkit is the acting
     * software; it mints no Device resource, so the agent is named textually (see ADR-010).
     */
    private const string PROVENANCE_AGENT_DISPLAY = 'Ardenexal FHIR Tools — QuestionnaireResponse/$extract';

    /**
     * All collaborators default to standalone instances so the service is usable without a container.
     */
    public function __construct(
        private readonly SafeExtensionReader $extensionReader = new SafeExtensionReader(),
        private readonly DefinitionPathWriter $writer = new DefinitionPathWriter(new PropertyMetadataProvider()),
        private readonly FHIRPathService $fhirPath = new FHIRPathService(),
        private readonly FHIRMetadataExtractor $metadata = new FHIRMetadataExtractor(),
        private readonly TemplateExtractor $templateExtractor = new TemplateExtractor(new FHIRPathService(), new PropertyMetadataProvider()),
    ) {
    }

    /**
     * Run observation-, definition-, and template-based extraction over a QuestionnaireResponse and
     * assemble the results into a single transaction Bundle (plus an optional companion OperationOutcome).
     */
    public function extract(object $questionnaireResponse, ExtractContext $context): ExtractResult
    {
        $version = $context->fhirVersion;
        $factory = new ExtractModelFactory($version);

        /** @var list<object> $issues */
        $issues = [];

        // The output version is governed by the context. Extraction grafts values read from the response
        // into freshly-built resources of the requested version; those values are version-specific model
        // objects (e.g. an R4 `DatePrimitive` cannot assign to an R5 `?DatePrimitive` property), so a
        // response whose own model version disagrees cannot be coherently extracted. Refuse cleanly with a
        // diagnostic rather than attempt a cross-version graft that would emit a malformed Bundle or crash.
        $qrVersion = $this->metadata->extractFHIRVersion($questionnaireResponse);
        if ($qrVersion !== null && $qrVersion !== $version->value) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf('QuestionnaireResponse model version "%s" differs from the requested extraction version "%s"; no resources were extracted.', $qrVersion, $version->value),
            );

            return new ExtractResult(
                $this->assembleTransactionBundle([], $factory),
                $this->buildOutcome([], $issues, $factory),
            );
        }

        $questionnaire = $context->questionnaire;
        $itemIndex     = is_object($questionnaire)
            ? $this->indexQuestionnaireItems($this->childItems($questionnaire))
            : [];

        // Allocate one urn:uuid per declared `extractAllocateId` variable and expose them to every
        // FHIRPath expression in this run as external constants (`%name`). Scope is treated as global
        // (names are unique across the corpus); item-scoped shadowing is not modelled.
        $allocatedIds = $this->collectAllocatedIds($questionnaire);
        // Bind %resource/%rootResource to the QR root once, up front. `definitionExtractValue`
        // expressions are then evaluated with the current QR response *item* as focus (%context)
        // while %resource stays the QR root. resourceNode is never mutated by the evaluator's
        // per-call setRootResource(), so it remains a stable QR-root handle for the whole walk.
        $evalContext = new EvaluationContext(
            rootResource: $questionnaireResponse,
            externalConstants: $allocatedIds,
            resourceNode: $questionnaireResponse,
        );

        /** @var list<array{resource: object, fullUrl: string|null}> $entries */
        $entries = [];

        // Observation-based extraction is R4-only: it builds R4 datatypes directly. For a non-R4 run,
        // report (rather than silently drop) any observationExtract items instead of emitting the wrong
        // model version into the Bundle.
        if ($version === FhirVersion::R4 && $questionnaireResponse instanceof QuestionnaireResponseResource) {
            $observations = [];
            $this->collectObservations($questionnaireResponse->item, $itemIndex, $questionnaireResponse, $observations);
            foreach ($observations as $observation) {
                $entries[] = ['resource' => $observation, 'fullUrl' => null];
            }
        } elseif ($this->hasAnyObservationExtract($itemIndex)) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf('Observation-based extraction is only supported for R4; observationExtract items were skipped for %s.', $version->value),
            );
        }

        $this->collectDefinitionResources($this->childItems($questionnaireResponse), $itemIndex, $factory, $evalContext, $entries, $issues);

        // Template-based extraction: clone `contained` templates flagged by `templateExtract` and populate
        // them via FHIRPath. Version-generic like the definition path. Runs at the array level (see
        // TemplateExtractor), so the Questionnaire is re-serialized to a decoded array here.
        if (is_object($questionnaire)) {
            $serializer = FHIRSerializationService::createDefault($version);
            /** @var array<string, mixed>|null $questionnaireArray */
            $questionnaireArray = json_decode($serializer->serializeToJson($questionnaire), true);
            if (is_array($questionnaireArray)) {
                foreach ($this->templateExtractor->extract($questionnaireArray, $questionnaireResponse, $factory, $evalContext, $serializer, $issues) as $templateEntry) {
                    $entries[] = $templateEntry;
                }
            }
        }

        // Resolve every entry's fullUrl once, up front. Provenance.target must reference the *same*
        // fullUrls the entries ship with, so minting cannot be deferred to bundle assembly (which would
        // hand Provenance a different urn than the entry it attests).
        $entries = $this->resolveEntryFullUrls($entries);

        $extractedCount = count($entries);
        if ($context->emitProvenance && $entries !== []) {
            $entries[] = $this->buildProvenanceEntry($entries, $questionnaireResponse, $factory);
        }

        $bundle  = $this->assembleTransactionBundle($entries, $factory);
        // The informational "nothing extracted" note keys off extracted resources, not the Provenance
        // entry we may have appended above.
        $outcome = $this->buildOutcome($extractedCount === 0 ? [] : $entries, $issues, $factory);

        return new ExtractResult($bundle, $outcome);
    }

    /**
     * Index every Questionnaire item (recursively) by its linkId.
     *
     * @param list<object> $items
     *
     * @return array<string, object>
     */
    private function indexQuestionnaireItems(array $items): array
    {
        $index = [];
        foreach ($items as $item) {
            $linkId = $this->linkIdOf($item);
            if ($linkId !== null) {
                $index[$linkId] = $item;
            }
            $index += $this->indexQuestionnaireItems($this->childItems($item));
        }

        return $index;
    }

    /**
     * Walk the response item tree, appending an Observation for each answer under an extract-flagged item.
     * R4-only: the produced `Observation` and its datatypes are R4.
     *
     * @param array<int, object>        $responseItems
     * @param array<string, object>     $itemIndex
     * @param list<ObservationResource> $observations  accumulated by reference
     */
    private function collectObservations(
        array $responseItems,
        array $itemIndex,
        QuestionnaireResponseResource $response,
        array &$observations,
    ): void {
        foreach ($responseItems as $responseItem) {
            $linkId       = $this->linkIdOf($responseItem);
            $sourceItem   = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $codings      = $sourceItem !== null ? $this->itemCodings($sourceItem) : [];
            $extractsHere = $sourceItem !== null
                && $this->hasObservationExtract($sourceItem)
                && $codings !== [];

            if ($extractsHere) {
                $code = new CodeableConcept(coding: $codings);
                foreach ($this->answersOf($responseItem) as $answer) {
                    $observations[] = $this->buildObservation($code, $this->answerValue($answer), $response);
                    // Nested answer.item may itself carry extract-flagged questions.
                    $this->collectObservations($this->childItems($answer), $itemIndex, $response, $observations);
                }
            } else {
                foreach ($this->answersOf($responseItem) as $answer) {
                    $this->collectObservations($this->childItems($answer), $itemIndex, $response, $observations);
                }
            }

            $this->collectObservations($this->childItems($responseItem), $itemIndex, $response, $observations);
        }
    }

    /**
     * True when any indexed Questionnaire item carries a truthy `observationExtract` flag.
     *
     * @param array<string, object> $itemIndex
     */
    private function hasAnyObservationExtract(array $itemIndex): bool
    {
        foreach ($itemIndex as $item) {
            if ($this->hasObservationExtract($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * True when the Questionnaire item carries a truthy `observationExtract` flag extension.
     */
    private function hasObservationExtract(object $item): bool
    {
        foreach ($this->extensionReader->readSubExtensions($item) as $extension) {
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
     * @param list<object>                                        $responseItems
     * @param array<string, object>                               $itemIndex
     * @param list<array{resource: object, fullUrl: string|null}> $entries       accumulated by reference
     * @param list<object>                                        $issues        accumulated by reference
     */
    private function collectDefinitionResources(
        array $responseItems,
        array $itemIndex,
        ExtractModelFactory $factory,
        EvaluationContext $evalContext,
        array &$entries,
        array &$issues,
    ): void {
        foreach ($responseItems as $responseItem) {
            $linkId     = $this->linkIdOf($responseItem);
            $sourceItem = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $canonical  = $sourceItem !== null ? $this->definitionExtractCanonical($sourceItem) : null;

            if ($canonical === null || $sourceItem === null) {
                // Not an extraction root — descend looking for nested definitionExtract roots.
                $this->collectDefinitionResources($this->childItems($responseItem), $itemIndex, $factory, $evalContext, $entries, $issues);
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
            $this->walkDefinitionItems($this->childItems($responseItem), $itemIndex, $resource, [$rootType], $resource, $rootType, $factory, $evalContext, $issues);

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

        foreach ($this->answersOf($responseItem) as $answer) {
            try {
                $this->writer->writeLeaf($resource, $relative, $this->answerValue($answer));
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
            $linkId     = $this->linkIdOf($responseItem);
            $sourceItem = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $segments   = $sourceItem !== null ? $this->definitionSegments($sourceItem) : null;

            // Calculated fields declared on this item are written to their absolute path from the
            // resource root, independent of the hierarchical answer-write context.
            if ($sourceItem !== null) {
                $this->applyDefinitionExtractValues($sourceItem, $responseItem, $rootResource, $rootType, $factory, $evalContext, $issues);
            }

            // No definition (logical group) — recurse in the same context.
            if ($segments === null) {
                $this->walkDefinitionItems($this->childItems($responseItem), $itemIndex, $context, $basePath, $rootResource, $rootType, $factory, $evalContext, $issues);
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
                $this->walkDefinitionItems($this->childItems($responseItem), $itemIndex, $context, $basePath, $rootResource, $rootType, $factory, $evalContext, $issues);
                continue;
            }

            $answers    = $this->answersOf($responseItem);
            $childItems = $this->childItems($responseItem);

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
                    $this->writer->writeLeaf($context, $relative, $this->answerValue($answer));
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

            return $this->stringifyPrimitive($this->extensionReader->readValue($definition));
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
        $raw = property_exists($item, 'definition') ? $this->stringifyPrimitive($item->definition ?? null) : null;

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
        if (!is_object($questionnaire)) {
            return $ids;
        }

        // Root extensions/items are read tolerantly: a constructor-bypassed deserializer object leaves
        // untouched typed properties uninitialized (the model-init footgun), so direct access throws.
        $this->collectAllocateNames($this->extensionReader->readSubExtensions($questionnaire), $ids);
        $this->collectAllocateNamesFromItems($this->childItems($questionnaire), $ids);

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
     * @param list<object>          $items
     * @param array<string, string> $ids   accumulated by reference
     */
    private function collectAllocateNamesFromItems(array $items, array &$ids): void
    {
        foreach ($items as $item) {
            $this->collectAllocateNames($this->extensionReader->readSubExtensions($item), $ids);
            $this->collectAllocateNamesFromItems($this->childItems($item), $ids);
        }
    }

    /**
     * Read a model object's `item` array tolerantly — a constructor-bypassed deserializer object leaves
     * an untouched `item` property uninitialized, so direct access throws (the model-init footgun).
     * Version-agnostic: returns whatever object children are present, of any model version.
     *
     * @return list<object>
     */
    private function childItems(object $object): array
    {
        // `??` uses isset() semantics, reading an uninitialized typed property as "absent" not \Error.
        $items = property_exists($object, 'item') ? ($object->item ?? []) : [];
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, static fn (mixed $v): bool => is_object($v)));
    }

    /**
     * Read a response/Questionnaire item's `answer` array tolerantly.
     *
     * @return list<object>
     */
    private function answersOf(object $item): array
    {
        $answers = property_exists($item, 'answer') ? ($item->answer ?? []) : [];
        if (!is_array($answers)) {
            return [];
        }

        return array_values(array_filter($answers, static fn (mixed $v): bool => is_object($v)));
    }

    /**
     * Read a QuestionnaireResponse answer's `value` (a choice property) tolerantly, or null when absent.
     */
    private function answerValue(object $answer): mixed
    {
        return property_exists($answer, 'value') ? ($answer->value ?? null) : null;
    }

    /**
     * Read a Questionnaire item's `code` (a list of `Coding`) tolerantly. R4-only path — the codings
     * feed an R4 `CodeableConcept`, so only R4 `Coding` instances are kept.
     *
     * @return list<Coding>
     */
    private function itemCodings(object $item): array
    {
        $codes = property_exists($item, 'code') ? ($item->code ?? []) : [];
        if (!is_array($codes)) {
            return [];
        }

        return array_values(array_filter($codes, static fn (mixed $v): bool => $v instanceof Coding));
    }

    /**
     * Resolve an extraction root's `fullUrl` sub-expression (a FHIRPath string on the `definitionExtract`
     * extension) to the entry `fullUrl`, or null when absent — leaving a fresh `urn:uuid:` to be minted.
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
            $expression = $this->stringifyPrimitive($this->extensionReader->readValue($fullUrl));
            if ($expression === null) {
                return null;
            }

            try {
                // A `fullUrl` sub-expression is resolved against the QR root (its historical focus),
                // taken from the stable resourceNode handle rather than the evaluator-mutated root.
                return $this->stringifyPrimitive($this->evaluateToScalar($expression, $evalContext->getResourceNode(), $factory, $evalContext));
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

            $definition = $this->stringifyPrimitive($this->extensionReader->readValue($definitionExt));
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
                $expression = $this->expressionString($this->extensionReader->readValue($expressionExt));
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
     * Propagates evaluation failures so callers can surface a diagnostic issue for a malformed
     * expression rather than silently dropping the calculated value.
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
     * Ensure every entry carries a concrete `fullUrl`, minting a fresh `urn:uuid:` for any that resolved
     * none. Resolving once, before Provenance assembly, keeps `Provenance.target` references byte-identical
     * to the entries they attest.
     *
     * @param list<array{resource: object, fullUrl: string|null}> $entries
     *
     * @return list<array{resource: object, fullUrl: string}>
     */
    private function resolveEntryFullUrls(array $entries): array
    {
        $resolved = [];
        foreach ($entries as $entry) {
            $resolved[] = [
                'resource' => $entry['resource'],
                'fullUrl'  => $entry['fullUrl'] ?? $this->uuidUrn(),
            ];
        }

        return $resolved;
    }

    /**
     * Build the opt-in `Provenance` Bundle entry attesting the extraction: `target` references every
     * extracted resource by its resolved `fullUrl`, and `entity` (`role = source`) references the source
     * QuestionnaireResponse. Only called when at least one resource was extracted (a `Provenance.target`
     * is 1..*).
     *
     * @param list<array{resource: object, fullUrl: string}> $entries the already-fullUrl-resolved extracted entries
     *
     * @return array{resource: object, fullUrl: string|null}
     */
    private function buildProvenanceEntry(array $entries, object $questionnaireResponse, ExtractModelFactory $factory): array
    {
        $targetFullUrls = array_map(static fn (array $entry): string => $entry['fullUrl'], $entries);

        $qrId            = property_exists($questionnaireResponse, 'id') ? ($questionnaireResponse->id ?? null) : null;
        $qrIdString      = $this->stringifyPrimitive($qrId);
        $sourceReference = $qrIdString !== null ? 'QuestionnaireResponse/' . $qrIdString : 'QuestionnaireResponse';

        $provenance = $factory->provenance(
            $targetFullUrls,
            $sourceReference,
            (new \DateTimeImmutable('now'))->format('Y-m-d\TH:i:sP'),
            self::PROVENANCE_AGENT_DISPLAY,
        );

        return ['resource' => $provenance, 'fullUrl' => null];
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
     * @param list<array{resource: object, fullUrl: string|null}> $entries
     */
    private function assembleTransactionBundle(array $entries, ExtractModelFactory $factory): object
    {
        $bundleEntries = [];
        foreach ($entries as $entry) {
            $resource  = $entry['resource'];
            $type      = $this->resourceTypeOf($resource);
            $logicalId = $this->logicalIdOf($resource);

            [$method, $url] = $logicalId === null
                ? [HTTPVerb::post->value, $type]
                : [HTTPVerb::put->value, $type . '/' . $logicalId];

            $bundleEntries[] = $factory->bundleEntry(
                $entry['fullUrl'] ?? $this->uuidUrn(),
                $resource,
                $method,
                $url,
            );
        }

        return $factory->transactionBundle($bundleEntries);
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
     * @param list<array{resource: object, fullUrl: string|null}> $entries
     * @param list<object>                                        $issues
     */
    private function buildOutcome(array $entries, array $issues, ExtractModelFactory $factory): ?object
    {
        if ($entries === []) {
            $issues[] = $factory->issue(
                IssueSeverity::information->value,
                IssueType::informationalnote->value,
                'No resources were extracted from the QuestionnaireResponse.',
            );
        }

        return $issues === [] ? null : $factory->operationOutcome($issues);
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
     * Normalise an item's `linkId` (a StringPrimitive wrapper or raw string, of any version) to a plain
     * string, or null when absent/blank. Read tolerantly for constructor-bypassed objects.
     */
    private function linkIdOf(object $item): ?string
    {
        $linkId = property_exists($item, 'linkId') ? ($item->linkId ?? null) : null;

        return $this->stringifyPrimitive($linkId);
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

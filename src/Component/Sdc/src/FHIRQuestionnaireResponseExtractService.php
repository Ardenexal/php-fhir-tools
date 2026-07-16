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
use Ardenexal\FHIRTools\Component\Sdc\Extract\DefinitionExtractionWalker;
use Ardenexal\FHIRTools\Component\Sdc\Extract\QuestionnaireResponseReader;
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
        private readonly QuestionnaireResponseReader $qrReader = new QuestionnaireResponseReader(),
        ?DefinitionExtractionWalker $definitionWalker = null,
    ) {
        $this->definitionWalker = $definitionWalker
            ?? new DefinitionExtractionWalker($this->extensionReader, $this->writer, $this->qrReader, $this->fhirPath);
    }

    private readonly DefinitionExtractionWalker $definitionWalker;

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
            ? $this->indexQuestionnaireItems($this->qrReader->childItems($questionnaire))
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

        $this->definitionWalker->collect($this->qrReader->childItems($questionnaireResponse), $itemIndex, $factory, $evalContext, $entries, $issues);

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
            $linkId = $this->qrReader->linkIdOf($item);
            if ($linkId !== null) {
                $index[$linkId] = $item;
            }
            $index += $this->indexQuestionnaireItems($this->qrReader->childItems($item));
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
            $linkId       = $this->qrReader->linkIdOf($responseItem);
            $sourceItem   = $linkId     !== null ? ($itemIndex[$linkId] ?? null) : null;
            $codings      = $sourceItem !== null ? $this->itemCodings($sourceItem) : [];
            $extractsHere = $sourceItem !== null
                && $this->hasObservationExtract($sourceItem)
                && $codings !== [];

            if ($extractsHere) {
                $code = new CodeableConcept(coding: $codings);
                foreach ($this->qrReader->answersOf($responseItem) as $answer) {
                    $observations[] = $this->buildObservation($code, $this->qrReader->answerValue($answer), $response);
                    // Nested answer.item may itself carry extract-flagged questions.
                    $this->collectObservations($this->qrReader->childItems($answer), $itemIndex, $response, $observations);
                }
            } else {
                foreach ($this->qrReader->answersOf($responseItem) as $answer) {
                    $this->collectObservations($this->qrReader->childItems($answer), $itemIndex, $response, $observations);
                }
            }

            $this->collectObservations($this->qrReader->childItems($responseItem), $itemIndex, $response, $observations);
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
        $this->collectAllocateNamesFromItems($this->qrReader->childItems($questionnaire), $ids);

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
            $name = $this->qrReader->stringifyPrimitive($this->extensionReader->readValue($extension));
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
            $this->collectAllocateNamesFromItems($this->qrReader->childItems($item), $ids);
        }
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
        $qrIdString      = $this->qrReader->stringifyPrimitive($qrId);
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

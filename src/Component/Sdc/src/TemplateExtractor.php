<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProviderInterface;

/**
 * Template-based `QuestionnaireResponse/$extract` (SDC `templateExtract`).
 *
 * A third, independent extraction method (alongside observation- and definition-based): each
 * `Questionnaire.item` carrying a `templateExtract` extension names a `#contained` template resource;
 * that template is cloned once per matching QuestionnaireResponse item and populated by evaluating the
 * FHIRPath expressions carried in `templateExtractContext` (a focus-shift that can fan a repeating
 * element out to one clone per context result) and `templateExtractValue` (a value substitution) that
 * decorate the template's elements. See the SDC extraction spec
 * (https://build.fhir.org/ig/HL7/sdc/en/extraction.html#template-based-extraction).
 *
 * ## Representation — decoded-array mutation, typed-model output
 *
 * The reference engine (`@aehrc/sdc-template-extract`) mutates the template as untyped JSON; this port
 * mirrors that by cloning the template as a **decoded array** and transforming it (array clone is a true
 * deep copy — the decoded template holds only arrays/scalars, no shared object refs). The finished array
 * is then deserialised into the correct per-version **typed model** for the Bundle. One reconciliation is
 * required: a `templateExtractValue` that lands a scalar directly on a complex-typed element (the SDC IG
 * `extract-complex-template` writes `Observation.subject` this way, which the reference engine emits as a
 * malformed bare-string reference) is wrapped into the element's shape — `Reference` → `{reference: X}` —
 * via {@see PropertyMetadataProviderInterface} before deserialisation, because the typed deserialiser
 * rejects a scalar where a datatype object is expected. The extract conformance oracle reconciles the
 * shape divergence at comparison time (see `AbstractSdcConformanceTest`).
 *
 * ## FHIRPath focus / `%resource`
 *
 * Expressions are evaluated with the matching QR item (or a `templateExtractContext` result) as the
 * focus (`%context`/`$this`) while `%resource`/`%rootResource` stay bound to the QR root via the shared
 * {@see EvaluationContext}'s `resourceNode`, and the run's `extractAllocateId` variables are available as
 * external constants (`%NewPatientId`). This is the same focus/`%resource` split the definition-based
 * path proved (the evaluator's per-call `setRootResource()` mutates only a per-call clone, so the shared
 * context's `resourceNode` survives) — passing a non-null `fhirVersion` forces that defensive clone.
 */
final class TemplateExtractor
{
    /** SDC extension flagging a Questionnaire item whose answers extract via a contained template. */
    private const string TEMPLATE_EXTRACT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-templateExtract';

    /** SDC extension on a template element: a FHIRPath focus-shift (one element clone per result). */
    private const string TEMPLATE_EXTRACT_CONTEXT_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-templateExtractContext';

    /** SDC extension on a template element / primitive-extension sibling: a FHIRPath value substitution. */
    private const string TEMPLATE_EXTRACT_VALUE_URL = 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-templateExtractValue';

    /** Identity sentinel returned by the transform to mean "drop this element" (distinct from a null value). */
    private readonly object $remove;

    private ExtractModelFactory $factory;

    private EvaluationContext $evalContext;

    /**
     * @param FHIRPathService                   $fhirPath evaluates template context/value expressions
     * @param PropertyMetadataProviderInterface $metadata resolves complex-typed target elements (the scalar→Reference wrap)
     */
    public function __construct(
        private readonly FHIRPathService $fhirPath,
        private readonly PropertyMetadataProviderInterface $metadata,
    ) {
        $this->remove = new \stdClass();
    }

    /**
     * Produce one Bundle entry per template instance.
     *
     * @param array<string, mixed> $questionnaireArray    the source Questionnaire, decoded to an array
     *                                                    (carries `contained` templates and `item` flags)
     * @param object               $questionnaireResponse the typed QR (FHIRPath focus + `%resource` root)
     * @param list<object>         $issues                accumulated by reference
     *
     * @return list<array{resource: object, fullUrl: string|null}>
     */
    public function extract(
        array $questionnaireArray,
        object $questionnaireResponse,
        ExtractModelFactory $factory,
        EvaluationContext $evalContext,
        FHIRSerializationService $serializer,
        array &$issues,
    ): array {
        $this->factory     = $factory;
        $this->evalContext = $evalContext;

        $containedById = $this->indexContainedById($questionnaireArray);

        /** @var list<array{resource: object, fullUrl: string|null}> $entries */
        $entries = [];
        foreach ($this->discoverTemplates($questionnaireArray) as $template) {
            foreach ($this->extractTemplate($template, $containedById, $questionnaireResponse, $factory, $serializer, $issues) as $entry) {
                $entries[] = $entry;
            }
        }

        return $entries;
    }

    /**
     * Index the Questionnaire's `contained` resources by `id` (the `#id` a `templateExtract.template`
     * slice resolves against).
     *
     * @param array<string, mixed> $questionnaireArray
     *
     * @return array<string, array<string, mixed>>
     */
    private function indexContainedById(array $questionnaireArray): array
    {
        $byId = [];
        foreach ($this->listOf($questionnaireArray, 'contained') as $resource) {
            $id = $resource['id'] ?? null;
            if (is_string($id)) {
                $byId[$id] = $resource;
            }
        }

        return $byId;
    }

    /**
     * Resolve one `templateExtract` flag to its Bundle entries — one populated clone per matching QR item.
     * Returns an empty list (and records a diagnostic) when the referenced template is missing, is a
     * deferred Bundle template, or has an unresolvable resourceType.
     *
     * @param array{linkId: string, templateId: string, fullUrlExpr: string|null} $template
     * @param array<string, array<string, mixed>>                                 $containedById
     * @param list<object>                                                        $issues        by reference
     *
     * @return list<array{resource: object, fullUrl: string|null}>
     */
    private function extractTemplate(
        array $template,
        array $containedById,
        object $questionnaireResponse,
        ExtractModelFactory $factory,
        FHIRSerializationService $serializer,
        array &$issues,
    ): array {
        $templateArray = $containedById[$template['templateId']] ?? null;
        if (!is_array($templateArray)) {
            $issues[] = $this->warning($factory, \sprintf('templateExtract references contained template "#%s", which was not found on the Questionnaire.', $template['templateId']));

            return [];
        }

        $type = $templateArray['resourceType'] ?? null;

        // templateExtractBundle (the template is itself a Bundle whose entries are the extracted
        // resources) is deferred — no reference oracle exercises it. Surface a diagnostic and skip rather
        // than emit a Bundle nested inside the transaction Bundle. See backlog.
        if ($type === 'Bundle') {
            $issues[] = $this->warning($factory, \sprintf('templateExtract template "#%s" is a Bundle (templateExtractBundle); Bundle templates are not yet supported and were skipped.', $template['templateId']));

            return [];
        }

        $class = is_string($type) ? $factory->resolveResourceClass($type) : null;
        if ($class === null) {
            $issues[] = $this->warning($factory, \sprintf('templateExtract template "#%s" has an unresolvable resourceType "%s".', $template['templateId'], is_string($type) ? $type : '(none)'));

            return [];
        }

        $entries = [];
        foreach ($this->findResponseItems($questionnaireResponse, $template['linkId']) as $focus) {
            $entry = $this->buildInstance($templateArray, $focus, $class, $template, $serializer, $factory, $issues);
            if ($entry !== null) {
                $entries[] = $entry;
            }
        }

        return $entries;
    }

    /**
     * Populate one template clone against a single QR focus item and deserialise it into a typed
     * resource, or null when it prunes to nothing or fails to deserialise (recording a diagnostic).
     *
     * @param array<string, mixed>                                                $templateArray
     * @param class-string                                                        $class
     * @param array{linkId: string, templateId: string, fullUrlExpr: string|null} $template
     * @param list<object>                                                        $issues        by reference
     *
     * @return array{resource: object, fullUrl: string|null}|null
     */
    private function buildInstance(
        array $templateArray,
        object $focus,
        string $class,
        array $template,
        FHIRSerializationService $serializer,
        ExtractModelFactory $factory,
        array &$issues,
    ): ?array {
        try {
            // Substitution evaluates every templateExtractValue/templateExtractContext FHIRPath
            // expression on the template. A malformed expression must surface a warning and skip this
            // instance, not abort the whole extraction run (parity with the definition path).
            $populated = $this->transformValue($templateArray, $focus);
        } catch (\Throwable $e) {
            $issues[] = $this->warning($factory, \sprintf('templateExtract template "#%s" failed to evaluate an expression and was skipped: %s', $template['templateId'], $e->getMessage()));

            return null;
        }
        if (!is_array($populated)) {
            return null;
        }
        unset($populated['id']); // strip the placeholder id (e.g. patTemplate)

        $pruned = $this->prune($this->wrapComplexTargets($populated, $class));
        if (!is_array($pruned)) {
            return null;
        }

        try {
            $resource = $serializer->deserializeFromJson((string) json_encode($pruned), $class);
        } catch (\Throwable $e) {
            $issues[] = $this->warning($factory, \sprintf('templateExtract template "#%s" produced a resource that could not be deserialized: %s', $template['templateId'], $e->getMessage()));

            return null;
        }

        return [
            'resource' => $resource,
            'fullUrl'  => $this->resolveFullUrl($template['fullUrlExpr'], $focus),
        ];
    }

    /**
     * Build a `warning`/`processing` diagnostic issue for a skipped or failed template.
     */
    private function warning(ExtractModelFactory $factory, string $message): object
    {
        return $factory->issue(IssueSeverity::warning->value, IssueType::processingfailure->value, $message);
    }

    // -- Discovery -----------------------------------------------------------

    /**
     * Every `templateExtract` flag on the Questionnaire's items, keyed to the item's linkId. A
     * Questionnaire-root `templateExtract` (no owning item) is skipped — it has no QR item to match, per
     * the reference engine.
     *
     * @param array<string, mixed> $questionnaire
     *
     * @return list<array{linkId: string, templateId: string, fullUrlExpr: string|null}>
     */
    private function discoverTemplates(array $questionnaire): array
    {
        /** @var list<array{linkId: string, templateId: string, fullUrlExpr: string|null}> $out */
        $out = [];
        foreach ($this->listOf($questionnaire, 'item') as $item) {
            $this->collectTemplates($item, $out);
        }

        return $out;
    }

    /**
     * @param array<string, mixed>                                                      $item
     * @param list<array{linkId: string, templateId: string, fullUrlExpr: string|null}> $out  by reference
     */
    private function collectTemplates(array $item, array &$out): void
    {
        $linkId = $item['linkId'] ?? null;
        if (is_string($linkId)) {
            foreach ($this->listOf($item, 'extension') as $extension) {
                $descriptor = $this->parseTemplateExtract($extension, $linkId);
                if ($descriptor !== null) {
                    $out[] = $descriptor;
                }
            }
        }

        foreach ($this->listOf($item, 'item') as $child) {
            $this->collectTemplates($child, $out);
        }
    }

    /**
     * Parse a single `templateExtract` complex extension into a `{linkId, templateId, fullUrlExpr}`
     * descriptor, or null when it is a different extension or carries no resolvable `#template` reference.
     *
     * @param array<string, mixed> $extension
     *
     * @return array{linkId: string, templateId: string, fullUrlExpr: string|null}|null
     */
    private function parseTemplateExtract(array $extension, string $linkId): ?array
    {
        if (($extension['url'] ?? null) !== self::TEMPLATE_EXTRACT_URL) {
            return null;
        }

        $templateId  = null;
        $fullUrlExpr = null;
        foreach ($this->listOf($extension, 'extension') as $sub) {
            $url = $sub['url'] ?? null;
            if ($url === 'template') {
                $reference = $sub['valueReference']['reference'] ?? null;
                if (is_string($reference) && str_starts_with($reference, '#')) {
                    $templateId = substr($reference, 1);
                }
            } elseif ($url === 'fullUrl') {
                $value = $sub['valueString'] ?? null;
                if (is_string($value)) {
                    $fullUrlExpr = $value;
                }
            }
        }

        return $templateId === null ? null : ['linkId' => $linkId, 'templateId' => $templateId, 'fullUrlExpr' => $fullUrlExpr];
    }

    /**
     * The QR response items whose linkId matches (siblings sharing a linkId → repeated instances). A
     * matched item is not descended into (its children carry different linkIds).
     *
     * @return list<object>
     */
    private function findResponseItems(object $questionnaireResponse, string $linkId): array
    {
        /** @var list<object> $out */
        $out = [];
        $this->searchResponseItems($this->objectItems($questionnaireResponse), $linkId, $out);

        return $out;
    }

    /**
     * @param list<object> $items
     * @param list<object> $out   by reference
     */
    private function searchResponseItems(array $items, string $linkId, array &$out): void
    {
        foreach ($items as $item) {
            if ($this->linkIdOf($item) === $linkId) {
                $out[] = $item;
                continue;
            }
            $this->searchResponseItems($this->objectItems($item), $linkId, $out);
        }
    }

    // -- Template transform (array level) ------------------------------------

    /**
     * Transform a decoded template value against the current FHIRPath focus node. Returns the
     * transformed value, or {@see $remove} to signal the caller should drop the element.
     */
    private function transformValue(mixed $value, mixed $focus): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        return array_is_list($value)
            ? $this->transformList($value, $focus)
            : $this->transformObject($value, $focus);
    }

    /**
     * @param list<mixed> $list
     *
     * @return list<mixed>
     */
    private function transformList(array $list, mixed $focus): array
    {
        $out = [];
        foreach ($list as $element) {
            if (is_array($element) && !array_is_list($element) && ($contextExpr = $this->contextExpr($element)) !== null) {
                // templateExtractContext: fan the element out to one clone per context result. A context
                // result may be a scalar node (e.g. `answer.value` yields a string) — it still becomes the
                // focus for this element's value expressions, so it is not filtered out.
                $stripped = $this->stripSdcContext($element);
                foreach ($this->evaluate($contextExpr, $focus) as $contextNode) {
                    $clone = $this->transformValue($stripped, $contextNode);
                    if ($clone !== $this->remove) {
                        $out[] = $clone;
                    }
                }

                continue;
            }

            $transformed = $this->transformValue($element, $focus);
            if ($transformed !== $this->remove) {
                $out[] = $transformed;
            }
        }

        return $out;
    }

    /**
     * @param array<string, mixed> $object
     */
    private function transformObject(array $object, mixed $focus): mixed
    {
        // A templateExtractValue on the element's own extension replaces the whole element with the result.
        if (($valueExpr = $this->ownValueExpr($object)) !== null) {
            $results = $this->evaluate($valueExpr, $focus);

            return $results === [] ? $this->remove : $this->buildSingle($results);
        }

        $out = [];
        /** @var array<string, mixed> $overrides */
        $overrides = [];
        foreach ($object as $key => $value) {
            if ($key === 'extension') {
                $kept = $this->stripSdcExtensions($value);
                if ($kept !== []) {
                    $out['extension'] = $this->transformValue($kept, $focus);
                }
                continue;
            }

            if (str_starts_with($key, '_')) {
                // Primitive-extension sibling (`_field`): its templateExtractValue populates `field`.
                [$present, $built] = $this->buildFieldValue($value, $focus);
                if ($present) {
                    $overrides[substr($key, 1)] = $built;
                }
                continue;
            }

            $transformed = $this->transformValue($value, $focus);
            if ($transformed !== $this->remove) {
                $out[$key] = $transformed;
            }
        }

        // An evaluated `_field` value overrides any static placeholder on `field`; an empty result leaves
        // the static value untouched (so `effectiveDateTime: "1900-01-01"` survives an empty %resource.authored).
        foreach ($overrides as $key => $value) {
            $out[$key] = $value;
        }

        return $out;
    }

    /**
     * Build the value for a `_field` primitive-extension sibling. A list-shaped `_field` (e.g. `_given`)
     * yields an array of every result; an object-shaped `_field` yields a single value. Returns
     * `[present, value]` — `present` is false when every expression evaluated empty.
     *
     * @return array{0: bool, 1: mixed}
     */
    private function buildFieldValue(mixed $fieldValue, mixed $focus): array
    {
        if (is_array($fieldValue) && array_is_list($fieldValue)) {
            $all = [];
            foreach ($fieldValue as $holder) {
                if (is_array($holder) && ($expr = $this->ownValueExpr($holder)) !== null) {
                    foreach ($this->evaluate($expr, $focus) as $result) {
                        $all[] = $this->scalarize($result);
                    }
                }
            }

            return $all === [] ? [false, null] : [true, $all];
        }

        if (is_array($fieldValue) && ($expr = $this->ownValueExpr($fieldValue)) !== null) {
            $results = $this->evaluate($expr, $focus);

            return $results === [] ? [false, null] : [true, $this->buildSingle($results)];
        }

        return [false, null];
    }

    /**
     * The first result reduced to a JSON-insertable value; a `Coding` collapses to `{system,code,display}`.
     *
     * @param non-empty-list<mixed> $results
     */
    private function buildSingle(array $results): mixed
    {
        return $this->scalarize($results[0]);
    }

    /**
     * Reduce a single FHIRPath result to a JSON-insertable scalar/array, preserving numeric and boolean
     * types and collapsing a `Coding` to its `{system,code,display}` leaves (matching the reference engine).
     */
    private function scalarize(mixed $result): mixed
    {
        if (is_scalar($result) || $result === null) {
            return $result;
        }

        if (is_object($result)) {
            if ((new \ReflectionClass($result))->getShortName() === 'Coding') {
                $coding = [];
                foreach (['system', 'code', 'display'] as $leaf) {
                    $value = $this->primitiveValue($result, $leaf);
                    if ($value !== null) {
                        $coding[$leaf] = $value;
                    }
                }

                return $coding;
            }

            // A primitive wrapper exposes its scalar through `->value`.
            if (property_exists($result, 'value')) {
                return $result->value ?? null;
            }
        }

        return null;
    }

    // -- Output shaping ------------------------------------------------------

    /**
     * Wrap a scalar that landed directly on a complex-typed root element into that element's shape, so the
     * typed deserialiser accepts it. Currently narrow: a scalar on a `Reference`-typed root property
     * becomes `{reference: <scalar>}` (the `Observation.subject` case). Broaden if a future template writes
     * a scalar onto another complex root element.
     *
     * @param array<string, mixed> $resource
     * @param class-string         $class
     *
     * @return array<string, mixed>
     */
    private function wrapComplexTargets(array $resource, string $class): array
    {
        foreach ($this->metadata->getPropertyMetadata($class) as $property => $meta) {
            $jsonKey = $meta->jsonKey ?? $property;
            if ($meta->fhirType !== 'Reference' || !isset($resource[$jsonKey]) || !is_scalar($resource[$jsonKey])) {
                continue;
            }
            $resource[$jsonKey] = ['reference' => $resource[$jsonKey]];
        }

        return $resource;
    }

    /**
     * Deep-prune null values and empty arrays/objects (matching the reference engine's `cleanDeep`), so
     * elements whose context/value evaluated empty disappear. `false`, `0`, and `""` are preserved.
     */
    private function prune(mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        if (array_is_list($value)) {
            $out = [];
            foreach ($value as $element) {
                $pruned = $this->prune($element);
                if ($pruned !== null && $pruned !== []) {
                    $out[] = $pruned;
                }
            }

            return $out;
        }

        $out = [];
        foreach ($value as $key => $element) {
            $pruned = $this->prune($element);
            if ($pruned !== null && $pruned !== []) {
                $out[$key] = $pruned;
            }
        }

        return $out;
    }

    /**
     * Resolve the `fullUrl` slice expression to the entry fullUrl, or null to let the Bundle mint a
     * fresh `urn:uuid:`. A malformed expression falls back to null.
     */
    private function resolveFullUrl(?string $expression, mixed $focus): ?string
    {
        if ($expression === null) {
            return null;
        }

        try {
            $result = $this->evaluate($expression, $focus);
        } catch (\Throwable) {
            return null;
        }

        if ($result === []) {
            return null;
        }

        $value = $this->scalarize($result[0]);

        return is_string($value) && $value !== '' ? $value : null;
    }

    // -- FHIRPath ------------------------------------------------------------

    /**
     * Evaluate an expression with `$focus` as the FHIRPath focus, keeping `%resource` bound to the QR
     * root. A non-null fhirVersion forces the evaluator to clone the shared context per call.
     *
     * @return list<mixed>
     */
    private function evaluate(string $expression, mixed $focus): array
    {
        return array_values($this->fhirPath->evaluate(
            $expression,
            $focus,
            $this->evalContext,
            $this->factory->fhirVersionValue(),
        )->toArray());
    }

    // -- Small tolerant readers ---------------------------------------------

    /**
     * The templateExtractContext FHIRPath expression on an element's own extension array, or null.
     *
     * @param array<string, mixed> $element
     */
    private function contextExpr(array $element): ?string
    {
        return $this->sdcExpr($element, self::TEMPLATE_EXTRACT_CONTEXT_URL);
    }

    /**
     * The templateExtractValue FHIRPath expression on an element's own extension array, or null.
     *
     * @param array<string, mixed> $element
     */
    private function ownValueExpr(array $element): ?string
    {
        return $this->sdcExpr($element, self::TEMPLATE_EXTRACT_VALUE_URL);
    }

    /**
     * @param array<string, mixed> $element
     */
    private function sdcExpr(array $element, string $url): ?string
    {
        foreach ($this->listOf($element, 'extension') as $extension) {
            if (($extension['url'] ?? null) === $url) {
                $value = $extension['valueString'] ?? null;

                return is_string($value) ? $value : null;
            }
        }

        return null;
    }

    /**
     * Drop the templateExtractContext extension from an element (keeping any non-SDC extensions), returning
     * a copy suitable to fan out per context result.
     *
     * @param array<string, mixed> $element
     *
     * @return array<string, mixed>
     */
    private function stripSdcContext(array $element): array
    {
        $kept = $this->stripSdcExtensions($element['extension'] ?? null);
        if ($kept === []) {
            unset($element['extension']);
        } else {
            $element['extension'] = $kept;
        }

        return $element;
    }

    /**
     * Keep only non-SDC-template extensions from an `extension` array.
     *
     * @return list<mixed>
     */
    private function stripSdcExtensions(mixed $extensions): array
    {
        if (!is_array($extensions)) {
            return [];
        }

        $sdc  = [self::TEMPLATE_EXTRACT_URL, self::TEMPLATE_EXTRACT_CONTEXT_URL, self::TEMPLATE_EXTRACT_VALUE_URL];
        $kept = [];
        foreach ($extensions as $extension) {
            if (is_array($extension) && in_array($extension['url'] ?? null, $sdc, true)) {
                continue;
            }
            $kept[] = $extension;
        }

        return $kept;
    }

    /**
     * A decoded array's child list under `$key`, keeping only assoc-array elements.
     *
     * @param array<string, mixed> $node
     *
     * @return list<array<string, mixed>>
     */
    private function listOf(array $node, string $key): array
    {
        $value = $node[$key] ?? null;
        if (!is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $element) {
            if (is_array($element)) {
                /** @var array<string, mixed> $element */
                $out[] = $element;
            }
        }

        return $out;
    }

    /**
     * A model object's `item` children, read tolerantly (constructor-bypassed objects leave typed
     * properties uninitialised — the model-init footgun).
     *
     * @return list<object>
     */
    private function objectItems(object $object): array
    {
        $items = property_exists($object, 'item') ? ($object->item ?? []) : [];
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, static fn (mixed $v): bool => is_object($v)));
    }

    /**
     * A QR item's `linkId` as a plain string (unwrapping a primitive wrapper), or null when absent/blank.
     */
    private function linkIdOf(object $item): ?string
    {
        $linkId = property_exists($item, 'linkId') ? ($item->linkId ?? null) : null;
        if (is_string($linkId)) {
            return $linkId === '' ? null : $linkId;
        }
        if (is_object($linkId) && property_exists($linkId, 'value')) {
            $inner = $linkId->value ?? null;

            return is_string($inner) && $inner !== '' ? $inner : null;
        }

        return null;
    }

    /**
     * A named leaf of a datatype object (e.g. a `Coding`'s `system`/`code`/`display`) as a plain string,
     * unwrapping a primitive wrapper, or null when absent/blank.
     */
    private function primitiveValue(object $object, string $property): ?string
    {
        $value = property_exists($object, $property) ? ($object->{$property} ?? null) : null;
        if (is_string($value)) {
            return $value === '' ? null : $value;
        }
        if (is_object($value) && property_exists($value, 'value')) {
            $inner = $value->value ?? null;

            return is_string($inner) && $inner !== '' ? $inner : null;
        }

        return null;
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Populate;

use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\IssueType;
use Ardenexal\FHIRTools\Component\Sdc\PopulateContext;
use Ardenexal\FHIRTools\Component\Sdc\PopulateModelFactory;
use Ardenexal\FHIRTools\Component\Sdc\FHIRQuestionnairePopulateService;

/**
 * Observation-based population for `observationLinkPeriod` items: selects the most-recent supplied
 * `Observation` that matches an item's `code`, has an eligible status, and whose effective time falls
 * within the item's link-period window, and coerces its value to the item's answer.
 *
 * Offline-first: reads only the caller-supplied {@see PopulateContext::$dataProvider}; no live fetch.
 * No reference oracle exists for this mechanism (see `tests/SOURCES.md`); selection is spec-driven and
 * covered by deterministic unit tests. Extracted from {@see FHIRQuestionnairePopulateService}; behaviour
 * is unchanged. Diagnostics are returned (not mutated through a by-ref parameter) so the caller controls
 * where they land in the response `OperationOutcome`.
 *
 * @internal implementation detail of the `Sdc` population path; not part of the public API
 */
final class ObservationSelector
{
    private const string OBSERVATION_LINK_PERIOD_URL =
        'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-observationLinkPeriod';

    /**
     * Observation statuses eligible for observation-based population — a completed/authoritative result.
     * (SDC: preliminary/registered/cancelled/entered-in-error are excluded.)
     *
     * @var list<string>
     */
    private const array OBSERVATION_STATUSES = ['final', 'amended', 'corrected'];

    private readonly AnswerValueCoercer $coercer;

    /**
     * @param SafeExtensionReader     $extensions guarded reader for the `observationLinkPeriod` extension on
     *                                            deserializer-origin Questionnaire items
     * @param FhirPrimitiveReader     $primitives shared primitive-value reader
     * @param AnswerValueCoercer|null $coercer    answer coercer; defaults to one bound to the same $primitives
     */
    public function __construct(
        private readonly SafeExtensionReader $extensions = new SafeExtensionReader(),
        private readonly FhirPrimitiveReader $primitives = new FhirPrimitiveReader(),
        ?AnswerValueCoercer $coercer = null,
    ) {
        $this->coercer = $coercer ?? new AnswerValueCoercer($this->primitives);
    }

    /**
     * Whether an item carries an `observationLinkPeriod` extension (observation-based population).
     */
    public function hasLinkPeriod(object $item): bool
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
     * the answers (0 or 1) plus any information/warning issues raised (the data seam is absent, the item
     * has no code, nothing matches, or the match carries no value).
     *
     * @return array{answers: list<object>, issues: list<object>}
     */
    public function populate(object $item, ?string $itemType, string $linkId, PopulateContext $populateContext, PopulateModelFactory $factory): array
    {
        /** @var list<object> $issues */
        $issues = [];

        $provider = $populateContext->dataProvider;
        if ($provider === null) {
            $issues[] = $factory->issue(
                IssueSeverity::information->value,
                IssueType::informationalnote->value,
                \sprintf("Item '%s' uses observationLinkPeriod but no data provider was supplied; item left unanswered.", $linkId),
            );

            return ['answers' => [], 'issues' => $issues];
        }

        $itemKeys = $this->itemCodingKeys($item);
        if ($itemKeys === []) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf("Item '%s' has observationLinkPeriod but no item.code to match Observations against; item left unanswered.", $linkId),
            );

            return ['answers' => [], 'issues' => $issues];
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

            return ['answers' => [], 'issues' => $issues];
        }

        $value = $best->value ?? null;
        if ($value === null) {
            $issues[] = $factory->issue(
                IssueSeverity::warning->value,
                IssueType::processingfailure->value,
                \sprintf("The Observation matched for item '%s' carries no value; item left unanswered.", $linkId),
            );

            return ['answers' => [], 'issues' => $issues];
        }

        $coerced = $this->coercer->coerce($itemType, $value, $linkId, $factory, $issues);

        return [
            'answers' => $coerced !== null ? [$factory->answer($coerced)] : [],
            'issues'  => $issues,
        ];
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
                $this->primitives->parseTimestamp($this->primitives->stringify($value->start ?? null)),
                $this->primitives->parseTimestamp($this->primitives->stringify($value->end ?? null)),
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
        $amount = $this->primitives->stringify($duration->value ?? null);
        if ($amount === null || !is_numeric($amount)) {
            return null;
        }

        $unit    = $this->primitives->stringify($duration->code ?? null) ?? $this->primitives->stringify($duration->unit ?? null);
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
        $status = $this->primitives->codeOf($observation->status ?? null);

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
        $code = $this->primitives->stringify($coding->code ?? null);
        if ($code === null) {
            return null;
        }

        return ($this->primitives->stringify($coding->system ?? null) ?? '') . '|' . $code;
    }

    /**
     * The effective Unix timestamp of an Observation (`effectiveDateTime`/`effectiveInstant`, or a
     * `effectivePeriod`'s start/end), or null when unreadable.
     */
    private function observationEffectiveTimestamp(object $observation): ?int
    {
        $effective = $observation->effective ?? null;
        if (!\is_object($effective)) {
            return $this->primitives->parseTimestamp($this->primitives->stringify($effective));
        }

        if (property_exists($effective, 'start') || property_exists($effective, 'end')) {
            return $this->primitives->parseTimestamp($this->primitives->stringify($effective->start ?? null))
                ?? $this->primitives->parseTimestamp($this->primitives->stringify($effective->end ?? null));
        }

        return $this->primitives->parseTimestamp($this->primitives->stringify($effective));
    }

    /**
     * The object-valued extensions declared on a node (empty when none).
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
}

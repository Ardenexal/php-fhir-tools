<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Populate;

use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Sdc\Extract\ExtractModelFactory;

/**
 * Version-scoped construction of the model objects a `$populate` run assembles.
 *
 * The population service is otherwise version-agnostic — it reads whatever tolerant getters and
 * {@see SafeExtensionReader} hand it — but the
 * `QuestionnaireResponse` envelope, its items/answers, the answer `value[x]` primitive wrappers, and the
 * companion `OperationOutcome` must be instantiated from the correct per-version model namespace
 * (`Models\R4`, `Models\R4B`, `Models\R5`). This factory is the *single* place that resolves those
 * FQCNs from {@see FhirVersion} and performs the dynamic `new $fqcn(...)` construction — mirroring
 * {@see ExtractModelFactory} and the `FhirVersion::extensionFqcn()` convention (the version string is the
 * namespace segment). Concentrating it here keeps the service body statically typed.
 *
 * FHIR *codes* (`'in-progress'`, issue severities/types) are version-stable strings, so they are taken
 * as literals; only the wrapper classes are per-version.
 *
 * @internal implementation detail of the `Sdc` population path; not part of the public API
 */
final class PopulateModelFactory
{
    /**
     * @param FhirVersion $version the model namespace (`R4`/`R4B`/`R5`) every object this factory builds —
     *                             and the FHIRPath evaluation scope — is resolved from
     */
    public function __construct(
        private readonly FhirVersion $version,
    ) {
    }

    /**
     * The FHIR version string (`R4`/`R4B`/`R5`) this factory constructs for — also the value FHIRPath
     * evaluation must be scoped to, so the population service reads it here rather than hardcoding R4.
     */
    public function fhirVersionValue(): string
    {
        return $this->version->value;
    }

    /**
     * Assemble the generated `QuestionnaireResponse`.
     *
     * @param string|null  $canonical the source Questionnaire's canonical URL (`QuestionnaireResponse.questionnaire`), or null
     * @param string       $status    a QuestionnaireResponse status code (`in-progress` for a freshly populated QR)
     * @param object|null  $subject   a `Reference` for `QuestionnaireResponse.subject`, or null
     * @param string|null  $authored  an ISO-8601 dateTime for `QuestionnaireResponse.authored`, or null
     * @param list<object> $items     the top-level response items
     */
    public function questionnaireResponse(?string $canonical, string $status, ?object $subject, ?string $authored, array $items): object
    {
        $qrClass        = $this->fqcn('Resource\\QuestionnaireResponseResource');
        $statusClass    = $this->fqcn('DataType\\QuestionnaireResponseStatusType');
        $canonicalClass = $this->fqcn('Primitive\\CanonicalPrimitive');
        $dateTimeClass  = $this->fqcn('Primitive\\DateTimePrimitive');

        return new $qrClass(
            questionnaire: $canonical !== null ? new $canonicalClass(value: $canonical) : null,
            status: new $statusClass($status),
            subject: $subject,
            authored: $authored !== null ? new $dateTimeClass(value: FHIRDateTime::parse($authored)) : null,
            item: $items,
        );
    }

    /**
     * Build one `QuestionnaireResponse.item`.
     *
     * @param list<object> $answers    the item's answers (each carrying an answer `value[x]`)
     * @param list<object> $childItems nested response items (for group items)
     */
    public function responseItem(string $linkId, ?string $text, array $answers, array $childItems): object
    {
        $itemClass = $this->fqcn('Resource\\QuestionnaireResponse\\QuestionnaireResponseItem');

        return new $itemClass(
            linkId: $linkId,
            text: $text,
            answer: $answers,
            item: $childItems,
        );
    }

    /**
     * Build one `QuestionnaireResponse.item.answer` carrying the given `value[x]`.
     */
    public function answer(mixed $value): object
    {
        $answerClass = $this->fqcn('Resource\\QuestionnaireResponse\\QuestionnaireResponseItemAnswer');

        return new $answerClass(value: $value);
    }

    /**
     * Wrap a plain string as this version's `StringPrimitive`.
     *
     * Answer `value[x]` is a choice whose `decimal` variant is a *raw* `string` while the `string` variant
     * is a `StringPrimitive`. A raw string would serialize as `valueDecimal`; wrapping forces `valueString`.
     */
    public function stringValue(string $value): object
    {
        $class = $this->fqcn('Primitive\\StringPrimitive');

        return new $class(value: $value);
    }

    /**
     * Wrap a plain string as this version's `UriPrimitive` (for `url`-typed items → `valueUri`).
     */
    public function uriValue(string $value): object
    {
        $class = $this->fqcn('Primitive\\UriPrimitive');

        return new $class(value: $value);
    }

    /**
     * Wrap an ISO date string as this version's `DatePrimitive` (→ `valueDate`).
     */
    public function dateValue(string $value): object
    {
        $class = $this->fqcn('Primitive\\DatePrimitive');

        return new $class(value: FHIRDate::parse($value));
    }

    /**
     * Wrap an ISO dateTime string as this version's `DateTimePrimitive` (→ `valueDateTime`).
     */
    public function dateTimeValue(string $value): object
    {
        $class = $this->fqcn('Primitive\\DateTimePrimitive');

        return new $class(value: FHIRDateTime::parse($value));
    }

    /**
     * Wrap an ISO time string as this version's `TimePrimitive` (→ `valueTime`).
     */
    public function timeValue(string $value): object
    {
        $class = $this->fqcn('Primitive\\TimePrimitive');

        return new $class(value: FHIRTime::parse($value));
    }

    /**
     * Build a `Reference` from a reference string (e.g. `Patient/123`).
     */
    public function reference(string $reference): object
    {
        $class = $this->fqcn('DataType\\Reference');

        return new $class(reference: $reference);
    }

    /**
     * Build a single `OperationOutcome.issue` with the given severity/type codes and diagnostics text.
     */
    public function issue(string $severity, string $code, string $diagnostics): object
    {
        $severityClass = $this->fqcn('DataType\\IssueSeverityType');
        $codeClass     = $this->fqcn('DataType\\IssueTypeType');
        $issueClass    = $this->fqcn('Resource\\OperationOutcome\\OperationOutcomeIssue');

        return new $issueClass(
            severity: new $severityClass($severity),
            code: new $codeClass($code),
            diagnostics: $diagnostics,
        );
    }

    /**
     * Wrap collected issues in an `OperationOutcome`.
     *
     * @param list<object> $issues
     */
    public function operationOutcome(array $issues): object
    {
        $outcomeClass = $this->fqcn('Resource\\OperationOutcomeResource');

        return new $outcomeClass(issue: $issues);
    }

    /**
     * Build a fully-qualified model class name in this factory's version namespace.
     */
    private function fqcn(string $relative): string
    {
        return 'Ardenexal\\FHIRTools\\Component\\Models\\' . $this->version->value . '\\' . $relative;
    }
}

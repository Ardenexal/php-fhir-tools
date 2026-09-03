<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;

/**
 * Validates that a coded value conforms to the value set bound to a property.
 *
 * Enforces a #[FHIRValueSetBinding] constraint, branching on binding strength. `required`
 * bindings are checked against the generated backed-enum for the value set (falling back to the
 * terminology client when no enum exists); `extensible`/`preferred` bindings are checked via the
 * terminology client. `example` bindings are ignored. When no real terminology client is
 * configured, non-required checks are skipped and surfaced as a single fhir:unchecked-binding
 * INFO violation (issue #71) that does not affect overall validity.
 */
final class FHIRValueSetBindingValidator extends ConstraintValidator
{
    public const string DEFAULT_MISSING_ENUM_MESSAGE = 'Required binding for value set {{ url }} could not be validated: no enum class generated.';

    public const string DEFAULT_INVALID_VALUE_MESSAGE = 'The value {{ value }} is not a valid case of value set {{ url }}.';

    public const string DEFAULT_UNCHECKED_BINDING_MESSAGE = 'Terminology validation for value set {{ url }} was skipped: no terminology client is configured.';

    public const string DEFAULT_WRONG_DISPLAY_MESSAGE = 'The display {{ value }} is not valid for code {{ code }} in system {{ system }}; the terminology server gives {{ expected }}.';

    /**
     * @param string[]                            $enumNamespaceRoots Namespace roots to probe for generated enum classes,
     *                                                                e.g. ['Ardenexal\FHIRTools\Component\Models\R4\Enum']
     * @param FHIRTerminologyClientInterface|null $terminologyClient  Null or NullFHIRTerminologyClient → extensible/preferred checks
     *                                                                are skipped and surfaced as fhir:unchecked-binding INFO violations
     */
    public function __construct(
        private readonly FHIRValidationMessageRegistry $messageRegistry,
        private readonly array $enumNamespaceRoots = [],
        private readonly ?FHIRTerminologyClientInterface $terminologyClient = null,
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRValueSetBinding) {
            throw new UnexpectedTypeException($constraint, FHIRValueSetBinding::class);
        }

        if ($value === null) {
            return;
        }

        // An empty repeating element carries no codes, so there is no membership question to answer.
        // Only `null` was treated as absent before, which was enough while the deserializer left
        // repeating properties uninitialized (Symfony reads those as null). Once they are initialised
        // to `[]`, every unpopulated coded array — `ElementDefinition.type.aggregation` is the loudest —
        // reached validateRequired() and produced a "no enum class generated" warning about nothing.
        if ($value === []) {
            return;
        }

        // FHIR primitives that carry only extensions (e.g. `_status`) have no
        // underlying value. Cast-to-string yields "" — treat as absent so that
        // NotBlank handles the required-field check rather than this validator.
        if ($value instanceof \Stringable && (string) $value === '') {
            return;
        }

        if ($constraint->strength === 'required') {
            $this->validateRequired($value, $constraint);
        } else {
            $this->validateNonRequired($value, $constraint);
        }

        $this->validateDisplays($value, $constraint);
    }

    /**
     * Report a coding whose display the terminology server rejects.
     *
     * Runs after whichever membership branch applied, because the two questions are independent: a code
     * can belong to the value set and still be labelled wrongly. Membership is already reported above,
     * and the client returns no correction for a code it rejected, so the two cannot double up on one
     * coding.
     *
     * Warning, never error, following {@see FHIRQuestionnaireValidator}
     * which has reported display this way since it was the only caller. It is also what keeps this safe:
     * the conformance comparison counts error severity, so this rule cannot move a corpus case into
     * `ABOVE` however the harness is later wired.
     *
     * Silent unless a real terminology client is configured. Display text is not derivable from
     * anything this repository holds - the generated enums carry it as a docblock comment, not as data -
     * so a caller who configures nothing sees exactly what they saw before.
     *
     * The language question that governs this rule is answered by delegation. A display can be correct
     * in one declared language and wrong in another, and the server is the authority on that; asking it
     * is the whole point of {@see FHIRTerminologyClientInterface::validateCodingWithDisplay()}, which
     * returns a correction only when the display was actually rejected.
     */
    private function validateDisplays(mixed $value, FHIRValueSetBinding $constraint): void
    {
        if ($this->terminologyClient === null || $this->terminologyClient instanceof NullFHIRTerminologyClient) {
            return;
        }

        // Example-strength bindings are documentation only (ADR-004), display included.
        if ($constraint->strength === 'example') {
            return;
        }

        $override = $this->messageRegistry->getOverride('FHIRValueSetBindingDisplay');

        foreach ($this->displayCandidates($value) as [$system, $code, $display]) {
            $result = $this->terminologyClient->validateCodingWithDisplay($constraint->valueSetUrl, $system, $code, $display);

            if ($result->correctDisplay === null) {
                continue;
            }

            $this->context->buildViolation($override ?? self::DEFAULT_WRONG_DISPLAY_MESSAGE)
                ->setParameters([
                    '{{ value }}'    => $display,
                    '{{ code }}'     => $code,
                    '{{ system }}'   => $system,
                    '{{ expected }}' => $result->correctDisplay,
                ])
                ->setInvalidValue($display)
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();
        }
    }

    /**
     * Every coding that can be asked about, as `[system, code, display]`.
     *
     * All three are required and a coding missing any of them is skipped. A display cannot be judged
     * without the system that gives the code its meaning, and a coding carrying no display has made no
     * claim to check. A `code`-typed element never reaches here for the same reason: it holds a code
     * alone, with nowhere to put a label.
     *
     * @return list<array{string, string, string}>
     */
    private function displayCandidates(mixed $value): array
    {
        if (is_array($value)) {
            $candidates = [];
            foreach ($value as $item) {
                foreach ($this->displayCandidates($item) as $candidate) {
                    $candidates[] = $candidate;
                }
            }

            return $candidates;
        }

        if (!is_object($value)) {
            return [];
        }

        $coding = $this->readPublicProperty($value, 'coding');
        if (is_array($coding)) {
            return $this->displayCandidates($coding);
        }

        $system  = self::readableString($this->readPublicProperty($value, 'system'));
        $code    = self::readableString($this->readPublicProperty($value, 'code'));
        $display = self::readableString($this->readPublicProperty($value, 'display'));

        if ($system === null || $code === null || $display === null) {
            return [];
        }

        return [[$system, $code, $display]];
    }

    /** A non-empty string out of a primitive wrapper or a bare string, or null for anything else. */
    private static function readableString(mixed $value): ?string
    {
        if (!self::isTestableCode($value)) {
            return null;
        }

        $string = (string) $value;

        return $string === '' ? null : $string;
    }

    private function validateRequired(mixed $value, FHIRValueSetBinding $constraint): void
    {
        $enumFqcn = $this->resolveBoundEnum($constraint);

        if ($enumFqcn === null) {
            if ($this->terminologyClient !== null) {
                $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
                foreach ($this->codeValues($value) as $item) {
                    if (!$this->terminologyClient->validateCode($constraint->valueSetUrl, $item)) {
                        $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                            ->setParameters(['{{ value }}' => (string) $item, '{{ url }}' => $constraint->valueSetUrl])
                            ->setInvalidValue($item)
                            ->setCode(FHIRViolationCode::ERROR)
                            ->addViolation();
                    }
                }

                return;
            }

            $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
            $this->context->buildViolation($override ?? self::DEFAULT_MISSING_ENUM_MESSAGE)
                ->setParameters(['{{ url }}' => $constraint->valueSetUrl])
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        /** @var class-string<\BackedEnum> $enumFqcn */
        $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');

        foreach ($this->codeValues($value) as $item) {
            if (!$this->isValidEnumCase($enumFqcn, $item)) {
                $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                    ->setParameters(['{{ value }}' => (string) $item, '{{ url }}' => $constraint->valueSetUrl])
                    ->setInvalidValue($item)
                    ->setCode(FHIRViolationCode::ERROR)
                    ->addViolation();
            }
        }
    }

    /**
     * Validates extensible/preferred bindings via the terminology client.
     * When no real client is configured (null or NullFHIRTerminologyClient), the check is
     * skipped and a single fhir:unchecked-binding INFO violation surfaces the coverage gap
     * (issue #71); it does not affect FHIRValidationReport::isValid().
     * Violations use WARNING by default; strict=true escalates to ERROR.
     * When maxValueSetUrl is set, values outside it always produce ERROR regardless of strict.
     */
    private function validateNonRequired(mixed $value, FHIRValueSetBinding $constraint): void
    {
        // Example-strength bindings are documentation only (ADR-004): never validated,
        // never surfaced as unchecked. The generator does not emit the attribute for them
        // (FHIRModelGenerator::shouldEmitBindingAttribute), so this guards hand-written
        // constraints and future generator changes.
        if ($constraint->strength === 'example') {
            return;
        }

        if ($this->terminologyClient === null || $this->terminologyClient instanceof NullFHIRTerminologyClient) {
            $override = $this->messageRegistry->getOverride('FHIRValueSetBindingUnchecked');
            $this->context->buildViolation($override ?? self::DEFAULT_UNCHECKED_BINDING_MESSAGE)
                ->setParameters(['{{ url }}' => $constraint->valueSetUrl])
                ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
                ->addViolation();

            return;
        }

        $override    = $this->messageRegistry->getOverride('FHIRValueSetBinding');
        $bindingCode = $constraint->strict ? FHIRViolationCode::ERROR : FHIRViolationCode::WARNING;

        foreach ($this->codeValues($value) as $item) {
            if (!$this->terminologyClient->validateCode($constraint->valueSetUrl, $item)) {
                $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                    ->setParameters(['{{ value }}' => (string) $item, '{{ url }}' => $constraint->valueSetUrl])
                    ->setInvalidValue($item)
                    ->setCode($bindingCode)
                    ->addViolation();
            }

            if ($constraint->maxValueSetUrl !== null && !$this->terminologyClient->validateCode($constraint->maxValueSetUrl, $item)) {
                $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
                    ->setParameters(['{{ value }}' => (string) $item, '{{ url }}' => $constraint->maxValueSetUrl])
                    ->setInvalidValue($item)
                    ->setCode(FHIRViolationCode::ERROR)
                    ->addViolation();
            }
        }
    }

    /**
     * @param class-string<\BackedEnum> $enumFqcn
     */
    private function isValidEnumCase(string $enumFqcn, mixed $value): bool
    {
        if ($value instanceof $enumFqcn) {
            return true;
        }

        // Array properties (isArray: true) pass the whole PHP array here;
        // validate each element individually.
        if (is_array($value)) {
            foreach ($value as $item) {
                if (!$this->isValidEnumCase($enumFqcn, $item)) {
                    return false;
                }
            }

            return true;
        }

        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        if (is_string($value) || is_int($value)) {
            return $enumFqcn::tryFrom($value) !== null;
        }

        return false;
    }

    /**
     * Resolve a generated enum for this value set, or null when membership cannot be decided here.
     *
     * The backed check is load-bearing, not defensive. `enum_exists()` is also true for a *pure*
     * enum, and some value sets cannot be enumerated at all — `AllLanguages` covers the whole of
     * BCP-47, so the generator emits `enum AllLanguages {}` with no cases and no backing type.
     * Calling `tryFrom()` on that is a fatal `Error: Call to undefined method`, which took out four
     * R5 cases mid-validation. Returning null instead routes to the existing "no generated enum
     * class" warning — the honest answer for a binding we cannot check offline.
     *
     * @return class-string<\BackedEnum>|null
     */
    private function resolveEnumFqcn(string $className): ?string
    {
        foreach ($this->enumNamespaceRoots as $root) {
            $fqcn = $root . '\\' . $className;

            // Folds the existence, enum and backing checks into one question: a candidate name built
            // from a namespace root plus a class name is expected to miss most of the time.
            if (!$this->attributes->isBackedEnum($fqcn)) {
                continue;
            }

            return $fqcn;
        }

        return null;
    }

    /**
     * The generated backed-enum bound to this constraint, or null when membership cannot be decided.
     *
     * Prefers `$constraint->enumClass`, which the generator resolves from the ValueSet's `name` via
     * `ClassNameResolver`. The URL cannot yield it: `.../ValueSet/item-type` names the class
     * `QuestionnaireItemType` and `http-verb` names `HTTPVerb`. The `classNameFromUrl()` fallback is
     * kept for hand-written constraints and models generated before the attribute carried the class.
     *
     * @return class-string<\BackedEnum>|null
     */
    private function resolveBoundEnum(FHIRValueSetBinding $constraint): ?string
    {
        if ($constraint->enumClass !== null) {
            $direct = $this->qualifyEnum($constraint->enumClass);
            if ($direct !== null && $this->enumDeclaresValueSet($direct, $constraint->valueSetUrl)) {
                return $direct;
            }
        }

        return $this->resolveEnumFqcn($this->classNameFromUrl($constraint->valueSetUrl));
    }

    /**
     * Accept either a bare class name or an already-qualified FQCN, applying the same backed-enum
     * check as `resolveEnumFqcn()`. That check is load-bearing rather than defensive: `AllLanguages`
     * covers the whole of BCP-47, so the generator emits a caseless, unbacked enum for it, and
     * calling `tryFrom()` on that is a fatal `Error`.
     *
     * @return class-string<\BackedEnum>|null
     */
    private function qualifyEnum(string $enumClass): ?string
    {
        if (str_contains($enumClass, '\\')) {
            return $this->isUsableBackedEnum($enumClass) ? $enumClass : null;
        }

        return $this->resolveEnumFqcn($enumClass);
    }

    /**
     * Whether an enum was generated from exactly the value set this binding names.
     *
     * The class name alone cannot answer this. `ClassNameResolver` maps
     * `.../ValueSet/medication-statement-status` and `.../ValueSet/medication-status` to the same name
     * `MedicationStatusCodes`, whose enum holds only the latter's three codes — binding on the name
     * alone rejected the legal `MedicationStatement.status` code `unknown`. The generator therefore
     * stamps each enum with its own source URL, and a mismatch here falls through to the "no enum
     * class generated" warning rather than producing a false positive.
     *
     * An enum with no `#[FHIRValueSetSource]` is rejected too: models generated before the attribute
     * existed cannot be verified, and an unverifiable enum is exactly what this guards against. Those
     * fall back to the URL-derived lookup, which is the pre-existing behaviour.
     *
     * @param class-string $enumFqcn
     */
    private function enumDeclaresValueSet(string $enumFqcn, string $boundValueSetUrl): bool
    {
        $attributes = $this->attributes->classAttributes($enumFqcn, FHIRValueSetSource::class);
        if ($attributes === []) {
            return false;
        }

        $declared = $attributes[0]->url;

        return self::bareUrl($declared) === self::bareUrl($boundValueSetUrl);
    }

    /** Strip the `|4.0.1` version suffix a binding URL carries but a value set's own URL does not. */
    private static function bareUrl(string $url): string
    {
        return (string) strstr($url, '|', true) ?: $url;
    }

    /** @phpstan-assert-if-true class-string<\BackedEnum> $fqcn */
    private function isUsableBackedEnum(string $fqcn): bool
    {
        return $this->attributes->isBackedEnum($fqcn);
    }

    /**
     * Whether a value can be tested for enum membership at all.
     *
     * A binding may be declared on a complex element (`CodeableConcept`, `Coding`), whose value is an
     * object that is not `Stringable`. Casting one for the violation message is a fatal `Error` — it
     * turned 12 R4 cases into `validate-crashed` the first time enum resolution was repaired. Deciding
     * membership from `coding[].code` is a separate capability; until it exists, decline rather than
     * crash or guess.
     */
    private static function isTestableCode(mixed $value): bool
    {
        return is_string($value) || is_int($value) || $value instanceof \Stringable;
    }

    /**
     * Every code a bound value carries, flattened.
     *
     * A binding lands on three shapes and only the first was ever checked. A `code`-typed element
     * arrives as a string or a primitive wrapper. A `Coding` arrives as an object holding the code one
     * level down. A `CodeableConcept` holds a list of `Coding`s, any of which can carry the bound code.
     * Before this existed, {@see isTestableCode()} rejected both objects outright on the required path,
     * and on the non-required path the whole object reached the terminology client, whose
     * implementations return their default for a non-scalar — so it passed. Every `CodeableConcept`
     * binding in the generated model went unchecked in both directions, `Condition.clinicalStatus` and
     * `AllergyIntolerance.clinicalStatus` among them.
     *
     * Only the code is read, never the system. A `CodeableConcept` whose coding carries the right code
     * under the wrong system therefore passes. That is a miss rather than a false positive, and the
     * conservative direction is the deliberate one: this rule is switched on across every coded element
     * at once, so it is written to under-report rather than to invent findings on data it has not seen.
     *
     * A `CodeableConcept` holding only `text` yields nothing and stays silent, which is the same
     * reasoning. FHIR allows a text-only concept, and reporting one would be a new class of finding
     * rather than the one this method exists to enable.
     *
     * @return list<mixed> values {@see isValidEnumCase()} and the terminology client can both read
     */
    private function codeValues(mixed $value): array
    {
        if (is_array($value)) {
            $codes = [];
            foreach ($value as $item) {
                foreach ($this->codeValues($item) as $code) {
                    $codes[] = $code;
                }
            }

            return $codes;
        }

        if (self::isTestableCode($value) || $value instanceof \BackedEnum) {
            return [$value];
        }

        if (!is_object($value)) {
            return [];
        }

        // CodeableConcept: every coding it carries is a candidate for the bound code.
        $coding = $this->readPublicProperty($value, 'coding');
        if (is_array($coding)) {
            return $this->codeValues($coding);
        }

        // Coding: the code sits one level down.
        $code = $this->readPublicProperty($value, 'code');

        return self::isTestableCode($code) || $code instanceof \BackedEnum ? [$code] : [];
    }

    /**
     * Read a public property, treating an uninitialized one as absent.
     *
     * Deserializers bypass the constructor, so a field the document omitted is uninitialized rather
     * than null and reading it directly throws.
     */
    private function readPublicProperty(object $node, string $property): mixed
    {
        if (!in_array($property, $this->models->publicPropertyNames($node), true)) {
            return null;
        }

        if (!$this->models->isPropertyInitialized($node, $property)) {
            return null;
        }

        return $this->models->readInitializedValue($node, $property);
    }

    private function classNameFromUrl(string $valueSetUrl): string
    {
        $urlPath = parse_url($valueSetUrl, PHP_URL_PATH) ?? $valueSetUrl;
        $name    = basename((string) $urlPath);
        $name    = (string) strstr($name, '|', true) ?: $name;

        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }
}

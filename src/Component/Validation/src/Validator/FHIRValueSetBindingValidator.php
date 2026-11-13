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
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyMembershipClientInterface;
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
 * INFO violation (issue #71) that does not affect overall validity. A terminology check the server
 * gave no answer to is surfaced the same way, never as an invalid code (issue #182).
 */
final class FHIRValueSetBindingValidator extends ConstraintValidator
{
    public const string DEFAULT_MISSING_ENUM_MESSAGE = 'Required binding for value set {{ url }} could not be validated: no enum class generated.';

    public const string DEFAULT_INVALID_VALUE_MESSAGE = 'The value {{ value }} is not a valid case of value set {{ url }}.';

    public const string DEFAULT_UNCHECKED_BINDING_MESSAGE = 'Terminology validation for value set {{ url }} was skipped: no terminology client is configured.';

    public const string DEFAULT_UNANSWERED_BINDING_MESSAGE = 'Terminology validation for value set {{ url }} was skipped: the terminology server gave no answer.';

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
            if ($this->terminologyClient !== null && !($constraint->fromProfile && $this->terminologyClient instanceof NullFHIRTerminologyClient)) {
                $this->checkGroupsWithClient($this->terminologyClient, $value, $constraint->valueSetUrl, FHIRViolationCode::ERROR);

                return;
            }

            // A profile's binding names a value set this library may never have seen. Saying so is
            // honest; "no enum class generated" would read as a fault in the generated models.
            if ($constraint->fromProfile) {
                $this->reportUnchecked($constraint->valueSetUrl);

                return;
            }

            $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');
            $this->context->buildViolation($override ?? self::DEFAULT_MISSING_ENUM_MESSAGE)
                ->setParameters(['{{ url }}' => $constraint->valueSetUrl])
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        foreach ($this->codeGroups($value) as $group) {
            foreach ($group as $coding) {
                if ($this->isValidEnumCase($enumFqcn, $coding['code'])) {
                    continue 2;
                }
            }

            $this->reportNotInValueSet($group, $constraint->valueSetUrl, FHIRViolationCode::ERROR);
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
            $this->reportUnchecked($constraint->valueSetUrl);

            return;
        }

        $bindingCode = $constraint->strict ? FHIRViolationCode::ERROR : FHIRViolationCode::WARNING;

        $answered = $this->checkGroupsWithClient($this->terminologyClient, $value, $constraint->valueSetUrl, $bindingCode);

        // One unchecked finding per value is enough; the max value set check would only repeat it.
        if ($constraint->maxValueSetUrl !== null) {
            $this->checkGroupsWithClient($this->terminologyClient, $value, $constraint->maxValueSetUrl, FHIRViolationCode::ERROR, reportUnanswered: $answered);
        }
    }

    /**
     * Ask the terminology client about every code group in the value, reporting each group with no member.
     *
     * A group is one concept: a `CodeableConcept`'s codings are alternatives, and the concept satisfies
     * the binding when any one of them is in the value set. A translation or a local code beside the
     * bound one is allowed, so it must not be reported on its own.
     *
     * A group the server could not answer for (see {@see FHIRTerminologyMembershipClientInterface}) is
     * neither passed nor failed. It is reported once, as `fhir:unchecked-binding`, because a refused
     * request says nothing about membership.
     *
     * @param string $valueSetUrl the value set to check against: the binding's own, or its max value set
     * @param string $failureCode severity of a group with no member
     *
     * @return bool true when every group got an answer
     */
    private function checkGroupsWithClient(
        FHIRTerminologyClientInterface $client,
        mixed $value,
        string $valueSetUrl,
        string $failureCode,
        bool $reportUnanswered = true,
    ): bool {
        $allAnswered = true;

        foreach ($this->codeGroups($value) as $group) {
            $verdict = $this->groupMembership($client, $valueSetUrl, $group);

            if ($verdict === null) {
                $allAnswered = false;
            } elseif ($verdict === false) {
                $this->reportNotInValueSet($group, $valueSetUrl, $failureCode);
            }
        }

        if (!$allAnswered && $reportUnanswered) {
            $this->reportUnchecked($valueSetUrl, self::DEFAULT_UNANSWERED_BINDING_MESSAGE);
        }

        return $allAnswered;
    }

    /**
     * Whether any coding in the group is in the value set: true on the first member, false when the
     * server rejected every coding, null when it rejected none it answered and left at least one unanswered.
     *
     * @param list<array{system: string|null, code: mixed}> $group
     */
    private function groupMembership(FHIRTerminologyClientInterface $client, string $valueSetUrl, array $group): ?bool
    {
        $verdict = false;

        foreach ($group as $coding) {
            $member = $this->codingMembership($client, $valueSetUrl, $coding['system'], $coding['code']);

            if ($member === true) {
                return true;
            }

            if ($member === null) {
                $verdict = null;
            }
        }

        return $verdict;
    }

    /**
     * Ask the client about one code, with its system when the coding carries one.
     *
     * Sending the system is what makes the question answerable: Ontoserver refuses `$validate-code`
     * with a code and no system, member or not. The code is passed as a plain string, because the
     * clients read a string, int or enum and treat a primitive wrapper object as no code at all.
     *
     * @return bool|null the client's verdict, or null when a client that can say so gave none
     */
    private function codingMembership(FHIRTerminologyClientInterface $client, string $valueSetUrl, ?string $system, mixed $code): ?bool
    {
        $code = $code instanceof \BackedEnum ? (string) $code->value : (string) $code;

        if ($client instanceof FHIRTerminologyMembershipClientInterface) {
            return $system === null ? $client->checkCode($valueSetUrl, $code) : $client->checkCoding($valueSetUrl, $system, $code);
        }

        return $system === null ? $client->validateCode($valueSetUrl, $code) : $client->validateCoding($valueSetUrl, $system, $code);
    }

    /**
     * One membership finding for a group with no member.
     *
     * A single code keeps the shape this finding has always had: the code as the value, the original
     * code object as the invalid value. A concept with several codings is one finding naming them all,
     * since it is the concept, not each coding, that fails the binding.
     *
     * @param list<array{system: string|null, code: mixed}> $group
     */
    private function reportNotInValueSet(array $group, string $valueSetUrl, string $severityCode): void
    {
        $override = $this->messageRegistry->getOverride('FHIRValueSetBinding');

        if (count($group) === 1) {
            $code    = $group[0]['code'];
            $label   = $code instanceof \BackedEnum ? (string) $code->value : (string) $code;
            $invalid = $code;
        } else {
            $label   = implode(', ', array_map(
                static fn (array $coding): string => $coding['code'] instanceof \BackedEnum ? (string) $coding['code']->value : (string) $coding['code'],
                $group,
            ));
            $invalid = $label;
        }

        $this->context->buildViolation($override ?? self::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => $label, '{{ url }}' => $valueSetUrl])
            ->setInvalidValue($invalid)
            ->setCode($severityCode)
            ->addViolation();
    }

    /**
     * One fhir:unchecked-binding INFO violation: the binding exists but could not be checked.
     *
     * @param string $valueSetUrl    the value set that went unchecked: the binding's own, or its max value set
     * @param string $defaultMessage why: nothing configured can check it, or the server gave no answer
     */
    private function reportUnchecked(string $valueSetUrl, string $defaultMessage = self::DEFAULT_UNCHECKED_BINDING_MESSAGE): void
    {
        $override = $this->messageRegistry->getOverride('FHIRValueSetBindingUnchecked');
        $this->context->buildViolation($override ?? $defaultMessage)
            ->setParameters(['{{ url }}' => $valueSetUrl])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->addViolation();
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
    private function resolveEnumFqcn(string $className, ?string $release = null): ?string
    {
        foreach ($this->enumNamespaceRoots as $root) {
            // A root belongs to a release when the release is one of its namespace segments
            if ($release !== null && !in_array($release, explode('\\', $root), true)) {
                continue;
            }

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

        $guessed = $this->resolveEnumFqcn($this->classNameFromUrl($constraint->valueSetUrl), $constraint->release);

        // A profile binding trusts only an enum generated from that very value set
        if ($constraint->fromProfile && $guessed !== null && !$this->enumDeclaresValueSet($guessed, $constraint->valueSetUrl)) {
            return null;
        }

        return $guessed;
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
     * turned 12 R4 cases into `validate-crashed` the first time enum resolution was repaired. Such an
     * object is never a code itself; {@see codeGroups()} reads the codes it carries.
     */
    private static function isTestableCode(mixed $value): bool
    {
        return is_string($value) || is_int($value) || $value instanceof \Stringable;
    }

    /**
     * Every code a bound value carries, grouped by the concept it belongs to.
     *
     * A binding lands on three shapes. A `code`-typed element arrives as a string or a primitive wrapper,
     * and is a group of one with no system. A `Coding` holds the code one level down, beside its system,
     * and is also a group of one. A `CodeableConcept` holds a list of `Coding`s that are alternatives for
     * one concept, so they form one group: the concept meets the binding when any one of them is a
     * member, and a translation or local code beside the bound one is not a defect. A repeating element
     * hands over a PHP array, and each item in it is its own group.
     *
     * The system is carried so the terminology server can be asked about the coding it actually is. The
     * generated enum path still ignores it: a right code under a wrong system passes there, a miss rather
     * than a false positive, kept because that rule runs across every coded element at once.
     *
     * A `CodeableConcept` holding only `text` yields no group and stays silent. FHIR allows a text-only
     * concept, and reporting one would be a new class of finding.
     *
     * @return list<list<array{system: string|null, code: mixed}>>
     */
    private function codeGroups(mixed $value): array
    {
        if (is_array($value)) {
            $groups = [];
            foreach ($value as $item) {
                foreach ($this->codeGroups($item) as $group) {
                    $groups[] = $group;
                }
            }

            return $groups;
        }

        if (self::isTestableCode($value) || $value instanceof \BackedEnum) {
            return [[['system' => null, 'code' => $value]]];
        }

        if (!is_object($value)) {
            return [];
        }

        // CodeableConcept: its codings are alternatives for one concept.
        $codings = $this->readPublicProperty($value, 'coding');
        if (is_array($codings)) {
            $group = [];
            foreach ($codings as $coding) {
                $entry = $this->codingEntry($coding);
                if ($entry !== null) {
                    $group[] = $entry;
                }
            }

            return $group === [] ? [] : [$group];
        }

        $entry = $this->codingEntry($value);

        return $entry === null ? [] : [[$entry]];
    }

    /**
     * The system and code of one `Coding`, or null when it carries no code.
     *
     * @return array{system: string|null, code: mixed}|null
     */
    private function codingEntry(mixed $coding): ?array
    {
        if (!is_object($coding)) {
            return null;
        }

        $code = $this->readPublicProperty($coding, 'code');
        if (!self::isTestableCode($code) && !$code instanceof \BackedEnum) {
            return null;
        }

        return ['system' => self::readableString($this->readPublicProperty($coding, 'system')), 'code' => $code];
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

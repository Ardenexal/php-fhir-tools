<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\ResourceType as R4ResourceType;
use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ResourceType as R4BResourceType;
use Ardenexal\FHIRTools\Component\Models\R5\Enum\ResourceType as R5ResourceType;
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Profile\TargetProfileBaseTypes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates that a Reference- or canonical-typed property points to a resource
 * conforming to at least one of the declared target profile URLs.
 *
 * Three checks need nothing resolved and always run:
 *
 * - **Reference type.** A literal reference of the RESTful shape `Patient/1` or `[base]/Patient/1`
 *   names the type of the resource it points at. When that type is not one the target profiles allow,
 *   the reference points at the wrong kind of resource. The HL7 reference validator only does this
 *   when told to trust reference URLs (its `assumeValidRestReferences` option); this validator always
 *   does, so an API contract that allows `Reference(PractitionerRole)` rejects `Patient/1` without
 *   resolving it.
 *
 * - **Canonical type.** A canonical of the RESTful shape `[base]/ValueSet/foo` names the type of the
 *   resource it identifies, and is compared with the allowed types the same way.
 *
 *   A core resource type's target profile (`http://hl7.org/fhir/StructureDefinition/Questionnaire`)
 *   allows that type. Any other target profile allows the type it constrains, looked up through
 *   {@see TargetProfileBaseTypes}. When any target is abstract (`Resource`) or a profile no source
 *   knows, either check is skipped, because that target could allow any type.
 * - **Conditional reference query.** The query of a conditional reference must be a list of
 *   `name=value` parameters joined by `&`.
 *
 * A constraint naming a `kind` applies to that kind of value only, so an element allowing
 * `Reference(Group) | canonical(EvidenceVariable)` checks each value against its own kind's targets.
 *
 * Profile conformance of the target is delegated to {@see FHIRReferenceResolverInterface}. When the
 * resolver returns null (including the NullFHIRReferenceResolver default), that check is skipped
 * silently. canonical-typed values (strings or Stringable objects) never reach the resolver because
 * they cannot be resolved to an in-process PHP object.
 *
 * @author Ardenexal
 */
final class FHIRTargetProfileValidator extends ConstraintValidator
{
    public const string DEFAULT_PROFILE_MISMATCH_MESSAGE = 'Resource does not conform to any declared target profile. Expected one of: {{ profiles }}. Actual profile URL(s): {{ actual }}.';

    public const string DEFAULT_UNVERIFIABLE_MESSAGE = 'Cannot verify target profile conformance: the resolved object carries no #[FHIRProfile] attribute. Expected one of: {{ profiles }}.';

    public const string CANONICAL_TYPE_MESSAGE = "Canonical URL '{{ canonical }}' refers to a resource that has the wrong type. Found {{ type }} expecting one of [{{ allowed }}]";

    public const string CONDITIONAL_QUERY_MESSAGE = 'The query part of the conditional reference is not a valid query string ({{ reference }})';

    public const string REFERENCE_TYPE_MESSAGE = 'Invalid Resource target type. Found {{ type }}, but expected one of ({{ allowed }})';

    /** Where every core resource type's StructureDefinition lives; a target profile under it names a type. */
    private const string CORE_PROFILE_BASE = 'http://hl7.org/fhir/StructureDefinition/';

    /** Abstract types a target can be declared as; any resource type is a valid target for them. */
    private const array ABSTRACT_TARGETS = ['Resource', 'DomainResource', 'CanonicalResource', 'MetadataResource'];

    /** A conditional reference: a resource type, then a search query (`Patient?identifier=x`). */
    private const string CONDITIONAL_REFERENCE = '~\A(?<type>[A-Z][A-Za-z]+)\?(?<query>.*)\z~s';

    /** A canonical whose path ends `[base]/Type/id`, after any `|version` and `#fragment` are removed. */
    private const string TYPED_CANONICAL = '~\Ahttps?://(?:[^/?#]*/)+(?<type>[A-Z][A-Za-z]+)/[A-Za-z0-9\-.]{1,64}\z~';

    /**
     * A literal reference whose path ends `Type/id`, optionally with `/_history/version`: relative
     * (`Patient/1`) or absolute (`https://example.org/fhir/Patient/1`).
     */
    private const string TYPED_REFERENCE = '~\A(?:https?://(?:[^/?#]*/)+)?(?<type>[A-Z][A-Za-z]+)/[A-Za-z0-9\-.]{1,64}(?:/_history/[A-Za-z0-9\-.]{1,64})?\z~';

    /** One search parameter name, modifiers and chains included (`subject:Patient.name`, `_has:Observation:patient:code`). */
    private const string QUERY_PARAMETER_NAME = '~\A[A-Za-z_][A-Za-z0-9_\-.:|]*\z~';

    public function __construct(
        private readonly FHIRReferenceResolverInterface $resolver,
        private readonly FHIRValidationMessageRegistry $messageRegistry,
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly TargetProfileBaseTypes $profileTypes = new TargetProfileBaseTypes(),
    ) {
    }

    /**
     * Validates that a value conforms to declared target profiles.
     *
     * Skips null values. Strings and Stringable objects (canonicals) get the canonical type
     * check only. For Reference objects and arrays of them, checks a conditional reference's
     * query, then delegates resolution to the configured resolver and checks the resolved
     * object's #[FHIRProfile] attribute against the target profiles. A constraint naming a kind
     * skips values of the other kind: a canonical under KIND_REFERENCE, an object under KIND_CANONICAL.
     *
     * @param mixed      $value      The property value to validate (null, string, object, array, or Stringable)
     * @param Constraint $constraint The #[FHIRTargetProfile] constraint with targetProfiles list
     *
     * @throws UnexpectedTypeException If constraint is not a FHIRTargetProfile instance
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRTargetProfile) {
            throw new UnexpectedTypeException($constraint, FHIRTargetProfile::class);
        }

        if ($value === null) {
            return;
        }

        foreach (is_array($value) ? $value : [$value] as $item) {
            // canonical-typed values (CanonicalPrimitive implements \Stringable, or raw string)
            if (is_string($item) || $item instanceof \Stringable) {
                if ($constraint->kind !== FHIRTargetProfile::KIND_REFERENCE && $this->isCanonical($item)) {
                    $this->checkCanonicalType($item, $constraint);
                }
            } elseif (is_object($item) && $constraint->kind !== FHIRTargetProfile::KIND_CANONICAL) {
                $this->validateItem($item, $constraint);
            }
        }
    }

    /**
     * Checks if a resolved reference object conforms to at least one target profile.
     *
     * Resolves the item using the configured resolver. If resolution fails (returns null),
     * validation is skipped. If the resolved object lacks a #[FHIRProfile] attribute, emits
     * a WARNING-level violation. If the object has a profile but it doesn't match any
     * declared target profile URL, emits an ERROR-level violation.
     *
     * @param object            $item       The reference object to validate
     * @param FHIRTargetProfile $constraint The constraint holding target profile URLs
     */
    private function validateItem(object $item, FHIRTargetProfile $constraint): void
    {
        $this->checkConditionalReference($item);

        // A reference already known to name the wrong type gets no second finding from its target
        if ($this->checkReferenceType($item, $constraint)) {
            return;
        }

        $resolved = $this->resolver->resolve($item);

        if ($resolved === null) {
            return;
        }

        $profileAttrs = $this->attributes->classAttributes($resolved, FHIRProfile::class);

        if ($profileAttrs === []) {
            $override = $this->messageRegistry->getOverride('FHIRTargetProfile');
            $this->context->buildViolation($override ?? self::DEFAULT_UNVERIFIABLE_MESSAGE)
                ->setParameters(['{{ profiles }}' => implode(', ', $constraint->targetProfiles)])
                ->setCode(FHIRViolationCode::WARNING)
                ->addViolation();

            return;
        }

        foreach ($profileAttrs as $profile) {
            if (in_array($profile->profileUrl, $constraint->targetProfiles, true)) {
                return;
            }
        }

        $actualUrls = array_map(
            static fn (FHIRProfile $profile): string => $profile->profileUrl,
            $profileAttrs,
        );

        $override = $this->messageRegistry->getOverride('FHIRTargetProfile');
        $this->context->buildViolation($override ?? self::DEFAULT_PROFILE_MISMATCH_MESSAGE)
            ->setParameters([
                '{{ profiles }}' => implode(', ', $constraint->targetProfiles),
                '{{ actual }}'   => implode(', ', $actualUrls),
            ])
            ->setInvalidValue($resolved)
            ->setCode(FHIRViolationCode::ERROR)
            ->addViolation();
    }

    /**
     * Check the query of a conditional reference (`Device?identifier=1234`).
     *
     * Only the query's shape is checked. The type a conditional reference names is a search over
     * that type, not a resource, so it is not compared to the allowed targets; a literal `Type/id`
     * reference is, by checkReferenceType().
     */
    private function checkConditionalReference(object $item): void
    {
        $reference = $this->referenceString($item);

        if ($reference === null || preg_match(self::CONDITIONAL_REFERENCE, $reference, $parts) !== 1) {
            return;
        }

        if (!$this->isResourceType($parts['type']) || $this->isValidQuery($parts['query'])) {
            return;
        }

        $this->context->buildViolation(self::CONDITIONAL_QUERY_MESSAGE)
            ->setParameter('{{ reference }}', $reference)
            ->setCode(FHIRViolationCode::ERROR)
            ->addViolation();
    }

    /**
     * Check the resource type a literal reference names in its path against the allowed targets.
     *
     * Only a reference of the RESTful shape `Type/id` names a type. A contained (`#p1`), `urn:uuid:`
     * or conditional reference does not, and is left alone.
     *
     * @return bool Whether the wrong type was reported
     */
    private function checkReferenceType(object $item, FHIRTargetProfile $constraint): bool
    {
        $reference = $this->referenceString($item);

        if ($reference === null || preg_match(self::TYPED_REFERENCE, $reference, $parts) !== 1) {
            return false;
        }

        $allowed = $this->allowedTypes($constraint, $this->fhirVersionOf($item, FHIRComplexType::class));
        $type    = $parts['type'];

        if ($allowed === null || !$this->isResourceType($type) || in_array($type, $allowed, true)) {
            return false;
        }

        $this->context->buildViolation(self::REFERENCE_TYPE_MESSAGE)
            ->setParameters([
                '{{ type }}'    => $type,
                '{{ allowed }}' => implode(', ', $allowed),
            ])
            ->setCode(FHIRViolationCode::ERROR)
            ->addViolation();

        return true;
    }

    /**
     * Check the resource type a canonical URL names in its path against the allowed targets.
     *
     * Only a canonical of the RESTful shape `[base]/Type/id` names a type. Many canonicals do not
     * (`http://loinc.org/vs/LL123`, `urn:oid:...`); those are left alone.
     */
    private function checkCanonicalType(string|\Stringable $value, FHIRTargetProfile $constraint): void
    {
        $canonical = (string) $value;
        $url       = explode('#', explode('|', $canonical, 2)[0], 2)[0];

        if (preg_match(self::TYPED_CANONICAL, $url, $parts) !== 1) {
            return;
        }

        $allowed = $this->allowedTypes($constraint, is_string($value) ? null : $this->fhirVersionOf($value, FHIRPrimitive::class));
        $type    = $parts['type'];

        if ($allowed === null || !$this->isResourceType($type) || in_array($type, $allowed, true)) {
            return;
        }

        $this->context->buildViolation(self::CANONICAL_TYPE_MESSAGE)
            ->setParameters([
                '{{ canonical }}' => $canonical,
                '{{ type }}'      => $type,
                '{{ allowed }}'   => implode(', ', $allowed),
            ])
            ->setCode(FHIRViolationCode::ERROR)
            ->addViolation();
    }

    /**
     * Whether a string-like value is a canonical, the only kind whose path is read as naming a type.
     *
     * A choice such as `PlanDefinition.action.definition[x]` carries the same target list on its
     * `uri` variant, and a `uri` is not an identifier of a definition: `definitionUri` may point at
     * `http://example.org/fhir/Patient/example` legitimately. The two cannot be told apart by class,
     * because `CanonicalPrimitive` extends `UriPrimitive`, so the generated `#[FHIRPrimitive]` type is
     * read instead. A bare string is a canonical generated as a plain string.
     */
    private function isCanonical(string|\Stringable $value): bool
    {
        if (is_string($value)) {
            return true;
        }

        foreach ($this->attributes->classAttributes($value, FHIRPrimitive::class) as $primitive) {
            if ($primitive->primitiveType === 'canonical') {
                return true;
            }
        }

        return false;
    }

    /**
     * The resource types the target profiles allow, or null when they cannot all be read as types.
     *
     * A core resource type's StructureDefinition names its type in its URL. Any other target, such as
     * an IG profile (`au-core-patient`) or a core profile (`vitalsigns`), is looked up for the type it
     * constrains. Null when any target is an abstract type such as `Resource`, or a profile whose type
     * no source knows: either way some type the URL names could still be a valid target, and only
     * resolving the reference would say which.
     *
     * @param string|null $fhirVersion Release of the value being checked, when its class says
     *
     * @return list<string>|null
     */
    private function allowedTypes(FHIRTargetProfile $constraint, ?string $fhirVersion): ?array
    {
        if ($constraint->targetProfiles === []) {
            return null;
        }

        $types = [];

        foreach ($constraint->targetProfiles as $profile) {
            $type = str_starts_with($profile, self::CORE_PROFILE_BASE) ? substr($profile, strlen(self::CORE_PROFILE_BASE)) : null;

            if ($type === null || (!in_array($type, self::ABSTRACT_TARGETS, true) && !$this->isResourceType($type))) {
                $type = $this->profileTypes->baseTypeOf($profile, $fhirVersion);
            }

            if ($type === null || in_array($type, self::ABSTRACT_TARGETS, true) || !$this->isResourceType($type)) {
                return null;
            }

            $types[] = $type;
        }

        return array_values(array_unique($types));
    }

    /**
     * The release a generated Reference or canonical class belongs to, from its own type attribute.
     *
     * @param class-string<FHIRComplexType|FHIRPrimitive> $attributeClass
     */
    private function fhirVersionOf(object $value, string $attributeClass): ?string
    {
        foreach ($this->attributes->classAttributes($value, $attributeClass) as $attribute) {
            return $attribute->fhirVersion;
        }

        return null;
    }

    /**
     * Whether every `&`-separated part of a conditional reference's query is a `name=value` parameter.
     * The value is not checked: search values are free text and may be empty.
     */
    private function isValidQuery(string $query): bool
    {
        if ($query === '') {
            return false;
        }

        foreach (explode('&', $query) as $parameter) {
            $equals = strpos($parameter, '=');

            if ($equals === false || preg_match(self::QUERY_PARAMETER_NAME, substr($parameter, 0, $equals)) !== 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * `Reference.reference` as a string: generated as a bare string or a string primitive wrapper.
     */
    private function referenceString(object $item): ?string
    {
        $reference = $this->models->readInitializedValue($item, 'reference');

        if (is_object($reference)) {
            $reference = $this->models->readInitializedValue($reference, 'value');
        }

        return is_string($reference) && $reference !== '' ? $reference : null;
    }

    /**
     * Whether a name is a resource type in any supported FHIR release. The constraint carries no
     * release, so a type that exists only in another release (`ActorDefinition` is R5-only) still
     * counts: an R4 canonical naming it is compared to the allowed targets rather than ignored.
     */
    private function isResourceType(string $type): bool
    {
        return R4ResourceType::tryFrom($type)   !== null
            || R4BResourceType::tryFrom($type)  !== null
            || R5ResourceType::tryFrom($type)   !== null;
    }
}

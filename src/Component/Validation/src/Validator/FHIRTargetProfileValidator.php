<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

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
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates that a Reference- or canonical-typed property points to a resource
 * conforming to at least one of the declared target profile URLs.
 *
 * Two checks need nothing resolved and always run:
 *
 * - **Canonical type.** A canonical of the RESTful shape `[base]/ValueSet/foo` names the type of the
 *   resource it identifies. When every target profile is a core resource type
 *   (`http://hl7.org/fhir/StructureDefinition/Questionnaire`) and the named type is not one of them,
 *   the canonical identifies the wrong kind of resource. A target profile that is not a core resource
 *   type, such as an IG profile or `Resource`, says nothing this validator can compare a type name to
 *   without resolving the profile, so the check is skipped.
 * - **Conditional reference query.** The query of a conditional reference must be a list of
 *   `name=value` parameters joined by `&`.
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

    /** Where every core resource type's StructureDefinition lives; a target profile under it names a type. */
    private const string CORE_PROFILE_BASE = 'http://hl7.org/fhir/StructureDefinition/';

    /** Abstract types a target can be declared as; any resource type is a valid target for them. */
    private const array ABSTRACT_TARGETS = ['Resource', 'DomainResource', 'CanonicalResource', 'MetadataResource'];

    /** A conditional reference: a resource type, then a search query (`Patient?identifier=x`). */
    private const string CONDITIONAL_REFERENCE = '~\A(?<type>[A-Z][A-Za-z]+)\?(?<query>.*)\z~s';

    /** A canonical whose path ends `[base]/Type/id`, after any `|version` and `#fragment` are removed. */
    private const string TYPED_CANONICAL = '~\Ahttps?://(?:[^/?#]*/)+(?<type>[A-Z][A-Za-z]+)/[A-Za-z0-9\-.]{1,64}\z~';

    /** One search parameter name, modifiers and chains included (`subject:Patient.name`, `_has:Observation:patient:code`). */
    private const string QUERY_PARAMETER_NAME = '~\A[A-Za-z_][A-Za-z0-9_\-.:|]*\z~';

    public function __construct(
        private readonly FHIRReferenceResolverInterface $resolver,
        private readonly FHIRValidationMessageRegistry $messageRegistry,
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
    ) {
    }

    /**
     * Validates that a value conforms to declared target profiles.
     *
     * Skips null values. Strings and Stringable objects (canonicals) get the canonical type
     * check only. For Reference objects and arrays of them, checks a conditional reference's
     * query, then delegates resolution to the configured resolver and checks the resolved
     * object's #[FHIRProfile] attribute against the target profiles.
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

        // canonical-typed values (CanonicalPrimitive implements \Stringable, or raw string)
        if (is_string($value) || $value instanceof \Stringable) {
            if ($this->isCanonical($value)) {
                $this->checkCanonicalType((string) $value, $constraint);
            }

            return;
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if (is_string($item) || $item instanceof \Stringable) {
                    if ($this->isCanonical($item)) {
                        $this->checkCanonicalType((string) $item, $constraint);
                    }
                } elseif (is_object($item)) {
                    $this->validateItem($item, $constraint);
                }
            }

            return;
        }

        if (is_object($value)) {
            $this->validateItem($value, $constraint);
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
     * Only the query's shape is checked. The type it names is not compared to the allowed targets:
     * the reference validator does that only when told to treat a reference URL as proof of its
     * target's type (its `assumeValidRestReferences` option), which is off by default, and on its
     * default settings it accepts `Practitioner/2` on an element that allows only `Organization`.
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
     * Check the resource type a canonical URL names in its path against the allowed targets.
     *
     * Only a canonical of the RESTful shape `[base]/Type/id` names a type. Many canonicals do not
     * (`http://loinc.org/vs/LL123`, `urn:oid:...`); those are left alone.
     */
    private function checkCanonicalType(string $canonical, FHIRTargetProfile $constraint): void
    {
        $allowed = $this->allowedTypes($constraint);

        if ($allowed === null) {
            return;
        }

        $url = explode('#', explode('|', $canonical, 2)[0], 2)[0];

        if (preg_match(self::TYPED_CANONICAL, $url, $parts) !== 1) {
            return;
        }

        $type = $parts['type'];

        if (!$this->isResourceType($type) || in_array($type, $allowed, true)) {
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
     * The resource types the target profiles allow, or null when they cannot be read as types.
     *
     * Null when any target is not a core resource type's StructureDefinition (an IG profile, a core
     * profile such as `vitalsigns`) or is an abstract type such as `Resource`: either way some type the
     * URL names could still be a valid target, and only resolving the profile would say which.
     *
     * @return list<string>|null
     */
    private function allowedTypes(FHIRTargetProfile $constraint): ?array
    {
        if ($constraint->targetProfiles === []) {
            return null;
        }

        $types = [];

        foreach ($constraint->targetProfiles as $profile) {
            if (!str_starts_with($profile, self::CORE_PROFILE_BASE)) {
                return null;
            }

            $type = substr($profile, strlen(self::CORE_PROFILE_BASE));

            if (in_array($type, self::ABSTRACT_TARGETS, true) || !$this->isResourceType($type)) {
                return null;
            }

            $types[] = $type;
        }

        return $types;
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

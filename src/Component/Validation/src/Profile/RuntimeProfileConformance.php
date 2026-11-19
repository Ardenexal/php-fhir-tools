<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRValueType;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Answers whether a value conforms to a profile, for slices discriminated by `profile`.
 *
 * Java's validator matches such a slice by validating the item against the profile on the side and
 * keeping the item when no error comes back. This does the same. The profile's rules are checked
 * in a validation run of their own, so its findings never reach the report: an item that fails the
 * profile is simply not in the slice, and the slicing's own rules say whether that matters.
 *
 * A value whose generated class declares the profile is checked against its own attributes. Any
 * other value is checked against the profile resolved at runtime, with its constraint ancestors,
 * under each one's own group. A profile no class declares and the resolver cannot find gets no
 * answer (null), and nor does a check nested deeper than MAX_DEPTH, which a profile that slices by
 * itself would otherwise recurse through forever.
 *
 * @author Ardenexal
 */
final class RuntimeProfileConformance
{
    /** How many profile checks may run inside one another. */
    public const int MAX_DEPTH = 4;

    private int $depth = 0;

    /** @var array<string, list<Constraint>> constraints per compiled profile URL, built once */
    private array $constraints = [];

    public function __construct(
        private readonly ?RuntimeProfileResolver $resolver,
    ) {
    }

    /**
     * Whether $value conforms to $profileUrl: true when its rules report no error, false when they do
     * or the profile constrains another type, null when the profile cannot be checked.
     */
    public function conforms(ValidatorInterface $validator, object $value, string $profileUrl): ?bool
    {
        if ($this->depth >= self::MAX_DEPTH) {
            return null;
        }

        ++$this->depth;
        try {
            return $this->check($validator, $value, $profileUrl);
        } finally {
            --$this->depth;
        }
    }

    private function check(ValidatorInterface $validator, object $value, string $profileUrl): ?bool
    {
        $canonical = self::canonicalWithoutVersion($profileUrl);

        foreach (self::attributesInHierarchy($value, FHIRProfile::class) as $profile) {
            if ($profile->profileUrl === $canonical) {
                return !self::hasError($validator->validate($value, null, [$canonical]));
            }
        }

        $fhirVersion = self::fhirVersionOf($value);
        if ($this->resolver === null || $fhirVersion === null) {
            return null;
        }

        $resolution = $this->resolver->resolve($profileUrl, $fhirVersion);
        if (!$resolution->isResolved()) {
            return null;
        }

        if ($resolution->rootType !== FHIRValueType::of($value)) {
            return false;
        }

        $constraints = [];
        $groups      = [];
        foreach ($resolution->chain as $compiled) {
            $groups[] = $compiled->profileUrl;
            if (!isset($this->constraints[$compiled->profileUrl])) {
                $this->constraints[$compiled->profileUrl] = [];
                foreach ($compiled->constraints() as $attribute) {
                    $constraint = $attribute->instantiate();
                    if ($constraint instanceof Constraint) {
                        $this->constraints[$compiled->profileUrl][] = $constraint;
                    }
                }
            }
            array_push($constraints, ...$this->constraints[$compiled->profileUrl]);
        }

        // The slice validator reads a runtime profile's slices from the profiles activated for the type
        $this->resolver->activate($fhirVersion, (string) $resolution->rootType, $resolution->chain);

        return !self::hasError($validator->validate($value, $constraints, $groups));
    }

    /**
     * Whether any finding is an error, read the way the validation report reads severity.
     */
    private static function hasError(ConstraintViolationListInterface $violations): bool
    {
        foreach ($violations as $violation) {
            if (!in_array($violation->getCode(), [
                FHIRViolationCode::WARNING,
                FHIRViolationCode::PROFILE_NOT_CHECKED,
                FHIRViolationCode::INFO,
                FHIRViolationCode::EVAL_ERROR,
                FHIRViolationCode::UNCHECKED_BINDING,
            ], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The release a generated class belongs to, from its resource or data type attribute.
     */
    private static function fhirVersionOf(object $value): ?string
    {
        $resource = self::attributesInHierarchy($value, FhirResource::class)[0] ?? null;
        if ($resource !== null) {
            return $resource->fhirVersion;
        }

        $complexType = self::attributesInHierarchy($value, FHIRComplexType::class)[0] ?? null;

        return $complexType?->fhirVersion;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $attributeClass
     *
     * @return list<T>
     */
    private static function attributesInHierarchy(object $value, string $attributeClass): array
    {
        $found = [];
        for ($class = new \ReflectionClass($value); $class !== false; $class = $class->getParentClass()) {
            foreach ($class->getAttributes($attributeClass) as $attribute) {
                $found[] = $attribute->newInstance();
            }
        }

        return $found;
    }

    private static function canonicalWithoutVersion(string $url): string
    {
        $bar = strpos($url, '|');

        return $bar === false ? $url : substr($url, 0, $bar);
    }
}

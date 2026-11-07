<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;

/**
 * Attribute reader that also answers with the slices of profiles resolved at runtime.
 *
 * `FHIRSliceConstraintValidator` checks one slice against its siblings: it reads every
 * `FHIRSliceConstraint` and `FHIRSlicingRules` declared on the validated object's class hierarchy
 * and keeps those in the active group. A generated profile class carries them as attributes; a base
 * resource validated against a runtime profile carries none, so the validator would find no slices
 * and enforce nothing. This decorator adds the compiled slice attributes of
 * every profile {@see RuntimeProfileResolver} has activated for the object's FHIR release and type.
 * The validator's own group filter then keeps only the profile being checked.
 *
 * A profile the object's class already declares is left out, so a generated profile instance never
 * sees its own slices twice. Every other read is passed to the inner reader unchanged.
 *
 * Wire it into the slice validator only. Other consumers gain nothing from it.
 *
 * @author Ardenexal
 */
final class RuntimeProfileAttributeReader implements FHIRAttributeReaderInterface
{
    /** Attribute classes this reader adds runtime instances for. */
    private const array SLICE_ATTRIBUTES = [FHIRSliceConstraint::class, FHIRSlicingRules::class];

    /**
     * Instantiated slice attributes per compiled profile, so repeated validations reuse them.
     *
     * @var \WeakMap<CompiledProfile, array<class-string, list<object>>>
     */
    private \WeakMap $instances;

    public function __construct(
        private readonly RuntimeProfileResolver $resolver,
        private readonly FHIRAttributeReaderInterface $inner = new FHIRAttributeReader(),
    ) {
        $this->instances = new \WeakMap();
    }

    public function classAttributes(object|string $subject, string $attributeClass): array
    {
        return $this->inner->classAttributes($subject, $attributeClass);
    }

    public function propertyAttributes(object|string $subject, string $property, string $attributeClass): array
    {
        return $this->inner->propertyAttributes($subject, $property, $attributeClass);
    }

    public function declaresInHierarchy(object|string $subject, string $attributeClass): bool
    {
        return $this->inner->declaresInHierarchy($subject, $attributeClass);
    }

    public function classAttributesInHierarchy(object|string $subject, string $attributeClass): array
    {
        $requested = $attributeClass;
        $declared  = $this->inner->classAttributesInHierarchy($subject, $attributeClass);
        if (!in_array($attributeClass, self::SLICE_ATTRIBUTES, true)) {
            return $declared;
        }

        $resources = $this->inner->classAttributesInHierarchy($subject, FhirResource::class);
        if ($resources === []) {
            return $declared;
        }

        $declaredProfiles = array_map(
            static fn (FHIRProfile $profile): string => $profile->profileUrl,
            $this->inner->classAttributesInHierarchy($subject, FHIRProfile::class),
        );

        $runtime = [];
        foreach ($this->resolver->resolvedProfilesFor($resources[0]->fhirVersion, $resources[0]->type) as $profile) {
            if (in_array($profile->profileUrl, $declaredProfiles, true)) {
                continue;
            }

            $runtime = [...$runtime, ...($this->instancesOf($profile)[$attributeClass] ?? [])];
        }

        return [...$declared, ...self::onlyInstancesOf($requested, $runtime)];
    }

    public function isBackedEnum(string $candidate): bool
    {
        return $this->inner->isBackedEnum($candidate);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     * @param list<object>    $candidates
     *
     * @return list<T>
     */
    private static function onlyInstancesOf(string $class, array $candidates): array
    {
        $matching = [];
        foreach ($candidates as $candidate) {
            if ($candidate instanceof $class) {
                $matching[] = $candidate;
            }
        }

        return $matching;
    }

    /**
     * @return array<class-string, list<object>>
     */
    private function instancesOf(CompiledProfile $profile): array
    {
        if (isset($this->instances[$profile])) {
            return $this->instances[$profile];
        }

        $byClass = [];
        foreach ($profile->attributes as $attribute) {
            if (in_array($attribute->class, self::SLICE_ATTRIBUTES, true)) {
                $byClass[$attribute->class][] = $attribute->instantiate();
            }
        }

        return $this->instances[$profile] = $byClass;
    }
}

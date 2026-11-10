<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * A profile's terminology binding (ElementDefinition.binding) on the element at `$path`.
 *
 * Like FHIRProfileInvariant, it sits on the class because a profile cannot annotate the properties
 * it inherits. Only `required` and `extensible` bindings are carried: `preferred` and `example`
 * bindings are guidance, not rules.
 *
 * Each occurrence is checked as a FHIRValueSetBinding on that value would check it, with two
 * differences:
 *
 *  - Only an enum generated from that exact value set is used. An enum whose class name merely
 *    matches the value set's URL is not trusted.
 *  - A required binding no generated enum or terminology client can check is reported as unchecked
 *    instead of as a missing enum.
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRProfileBinding extends Constraint
{
    /**
     * @param string            $path        Element path from the class, e.g. 'component.value[x]'
     * @param string            $valueSetUrl Canonical URL of the bound value set, as the profile writes it
     * @param string            $strength    'required' or 'extensible'
     * @param list<string>|null $groups      Validation groups (profile URLs) under which this binding is active
     */
    public function __construct(
        public readonly string $path,
        public readonly string $valueSetUrl,
        public readonly string $strength = 'required',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

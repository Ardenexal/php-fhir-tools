<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Fixture with a profile-group-gated #[FHIRProfileConstraint] attribute.
 * Violations are only generated when PROFILE_URL is included in the validation groups.
 */
#[FHIRProfileConstraint(
    path: 'identifier',
    constraint: Count::class,
    options: ['min' => 1],
    groups: [ProfileConstraintFixture::PROFILE_URL],
)]
final class ProfileConstraintFixture
{
    public const string PROFILE_URL = 'http://example.org/StructureDefinition/test-profile';

    /**
     * @param list<mixed> $identifier
     */
    public function __construct(
        public readonly array $identifier = [],
    ) {
    }
}

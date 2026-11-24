<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ListResource;

/**
 * A List profile whose entries are sliced closed on `item.resolve()`: one Condition and nothing else.
 */
#[FHIRProfile(profileUrl: ResolveSlicedListFixture::URL, baseType: 'List', fhirVersion: 'R4')]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'condition',
    min: 1,
    max: 1,
    discriminators: [['type' => 'type', 'path' => 'item.resolve()', 'value' => ['Condition']]],
    groups: [ResolveSlicedListFixture::URL],
)]
#[FHIRSlicingRules(property: 'entry', rules: 'closed', groups: [ResolveSlicedListFixture::URL])]
class ResolveSlicedListFixture extends ListResource
{
    public const string URL = 'http://example.org/fhir/StructureDefinition/resolve-sliced-list';
}

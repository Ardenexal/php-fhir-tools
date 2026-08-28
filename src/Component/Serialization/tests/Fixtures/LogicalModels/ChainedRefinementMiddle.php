<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\LogicalModels;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

/**
 * The middle link: a refinement that is itself refined.
 *
 * Its own `name` is a profile identifier, so stopping the walk here would emit `profile-middle` —
 * which is the failure the second loop iteration exists to prevent.
 *
 * The hyphen in that name is load-bearing, not decoration: the resolver only follows `refines` past
 * a definition whose name cannot be an element name, so renaming this to a plain identifier would
 * halt the walk here and break the two-hop test rather than the one-hop one.
 *
 * @see ChainedRefinementLeaf
 */
#[LogicalModel(
    url: 'http://example.test/StructureDefinition/profile-middle',
    name: 'profile-middle',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://example.test/StructureDefinition/ChainedBase',
)]
class ChainedRefinementMiddle extends ChainedRefinementBase
{
}

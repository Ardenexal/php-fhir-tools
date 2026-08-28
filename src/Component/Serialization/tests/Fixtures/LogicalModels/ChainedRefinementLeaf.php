<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\LogicalModels;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

/**
 * A refinement of a refinement: two hops from the type that names the element.
 *
 * Resolving this to `ChainedBase` requires the chain-following loop to run twice. A resolver that
 * took a single hop would emit `profile-middle`, and one that read `name` directly would emit
 * `profile-leaf`; both are profile identifiers and neither is an element name.
 */
#[LogicalModel(
    url: 'http://example.test/StructureDefinition/profile-leaf',
    name: 'profile-leaf',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://example.test/StructureDefinition/profile-middle',
)]
class ChainedRefinementLeaf extends ChainedRefinementMiddle
{
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\LogicalModels;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

/**
 * The type that names the element at the end of a two-hop refinement chain.
 *
 * The shipped CDA packages contain no refinement of a refinement — every `refines` link in them
 * resolves in one hop — so the chain-following loop in `LogicalModelLocatorTrait` has no natural
 * fixture. These three classes supply one, since the loop is what makes the resolution correct for
 * a package that later publishes such a chain.
 *
 * @see ChainedRefinementMiddle
 * @see ChainedRefinementLeaf
 */
#[LogicalModel(
    url: 'http://example.test/StructureDefinition/ChainedBase',
    name: 'ChainedBase',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class ChainedRefinementBase
{
    /** Carries one optional attribute so the serialized element is not degenerate. */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public ?string $classCode = null,
    ) {
    }
}

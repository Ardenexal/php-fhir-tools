<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Fixtures\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRSliceDiscriminator;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;

/**
 * Fixture profile class for FHIRIGRegistryCompilerPass slice-discriminator tests.
 *
 * Mirrors the shape of a generated AU IHI profile:
 *   - #[FHIRProfile] declares profileUrl / baseType / fhirVersion
 *   - Two #[FHIRSliceDiscriminator] attributes (value + pattern) declare the composite discriminator
 *   - extends Identifier so the compiler pass can resolve the base type FQCN from the parent chain
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org.au/fhir/StructureDefinition/au-ihi',
    baseType: 'Identifier',
    fhirVersion: 'R4',
)]
#[FHIRSliceDiscriminator(
    type: 'value',
    path: 'system',
    value: 'http://ns.electronichealth.net.au/id/hi/ihi/1.0',
)]
#[FHIRSliceDiscriminator(
    type: 'pattern',
    path: 'type',
    value: ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'NI']]],
)]
class AUIHIFixtureProfile extends Identifier
{
}
<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/example-composition
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/example-composition', baseType: 'Composition', fhirVersion: 'R4')]
#[FHIRSlicingRules(property: 'section', rules: 'closed', groups: ['http://hl7.org/fhir/StructureDefinition/example-composition'])]
#[FHIRSliceConstraint(
    property: 'section',
    sliceName: 'procedure',
    min: 0,
    max: '*',
    discriminatorType: 'pattern',
    discriminatorPath: 'code',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/example-composition'],
)]
#[FHIRSliceConstraint(
    property: 'section',
    sliceName: 'medications',
    min: 0,
    max: '*',
    discriminatorType: 'pattern',
    discriminatorPath: 'code',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/example-composition'],
)]
#[FHIRSliceConstraint(
    property: 'section',
    sliceName: 'plan',
    min: 0,
    max: '*',
    discriminatorType: 'pattern',
    discriminatorPath: 'code',
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/example-composition'],
)]
class DocumentStructureProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/example-composition';
}

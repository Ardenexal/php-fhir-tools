<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\LibraryResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqllibrary
 *
 * @description Represents a CQL logic library
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/cqllibrary', baseType: 'Library', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'],
)]
#[FHIRProfileMustSupport(path: 'parameter', groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'])]
#[FHIRProfileMustSupport(path: 'dataRequirement', groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'])]
class CQLlibraryProfile extends LibraryResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cqllibrary';
}

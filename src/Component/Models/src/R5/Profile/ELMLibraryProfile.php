<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/elmlibrary
 *
 * @description Represents an executable CQL logic library in translated ELM format
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/elmlibrary', baseType: 'Library', fhirVersion: 'R5')]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/elmlibrary'])]
#[FHIRSlicingRules(property: 'content', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/elmlibrary'])]
#[FHIRSliceConstraint(
    property: 'content',
    sliceName: 'elmXmlContent',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'contentType',
    discriminatorValue: 'application/elm+xml',
    orderedIndex: 0,
    rules: [
        [
            'path'       => 'contentType',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['max' => 1],
        ],
        [
            'path'       => 'contentType',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'application/elm+xml'],
        ],
        [
            'path'       => 'data',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/elmlibrary'],
)]
#[FHIRSliceConstraint(
    property: 'content',
    sliceName: 'elmJsonContent',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'contentType',
    discriminatorValue: 'application/elm+json',
    orderedIndex: 1,
    rules: [
        [
            'path'       => 'contentType',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['max' => 1],
        ],
        [
            'path'       => 'contentType',
            'constraint' => 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
            'options'    => ['value' => 'application/elm+json'],
        ],
        [
            'path'       => 'data',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1, 'max' => 1],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/elmlibrary'],
)]
class ELMLibraryProfile extends LogicLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/elmlibrary';
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\CompositionResource;

/**
 * @author Health Level Seven, Inc. - Structured Documents WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/clinicaldocument
 *
 * @description The Clinical Document profile constrains Composition to specify a clinical document (matching CDA).
 *
 * The base Composition is a general resource for compositions or documents about any kind of subject that might be encountered in healthcare including such things as guidelines, medicines, etc. A clinical document is focused on documents related to the provision of care process, where the subject is a patient, a group of patients, or a closely related concept. A clinical document has additional requirements around confidentiality that do not apply in the same way to other kinds of documents.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/clinicaldocument', baseType: 'Composition', fhirVersion: 'R5')]
class ClinicalDocumentProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/clinicaldocument';
}

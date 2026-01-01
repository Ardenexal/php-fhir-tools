<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtendedContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Organization
 *
 * @description A formally or informally recognized grouping of people or organizations formed for the purpose of achieving some form of collective action.  Includes companies, institutions, corporations, departments, community groups, healthcare practice groups, payer/insurer, etc.
 */
#[FhirResource(type: 'Organization', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Organization', fhirVersion: 'R5')]
class FHIROrganization extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifies this organization  across multiple systems */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether the organization's record is still in active use */
        public ?FHIRBoolean $active = null,
        /** @var array<FHIRCodeableConcept> type Kind of organization */
        public array $type = [],
        /** @var FHIRString|string|null name Name used for the organization */
        public FHIRString|string|null $name = null,
        /** @var array<FHIRString|string> alias A list of alternate names that the organization is known as, or was known as in the past */
        public array $alias = [],
        /** @var FHIRMarkdown|null description Additional details about the Organization that could be displayed as further information to identify the Organization beyond its name */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRExtendedContactDetail> contact Official contact details for the Organization */
        public array $contact = [],
        /** @var FHIRReference|null partOf The organization of which this organization forms a part */
        public ?FHIRReference $partOf = null,
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to services operated for the organization */
        public array $endpoint = [],
        /** @var array<FHIROrganizationQualification> qualification Qualifications, certifications, accreditations, licenses, training, etc. pertaining to the provision of care */
        public array $qualification = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Organization
 * @description A formally or informally recognized grouping of people or organizations formed for the purpose of achieving some form of collective action.  Includes companies, institutions, corporations, departments, community groups, healthcare practice groups, payer/insurer, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Organization', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Organization', fhirVersion: 'R5')]
class FHIROrganization extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Identifies this organization  across multiple systems */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean active Whether the organization's record is still in active use */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $active = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> type Kind of organization */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Name used for the organization */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> alias A list of alternate names that the organization is known as, or was known as in the past */
		public array $alias = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Additional details about the Organization that could be displayed as further information to identify the Organization beyond its name */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtendedContactDetail> contact Official contact details for the Organization */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference partOf The organization of which this organization forms a part */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $partOf = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> endpoint Technical endpoints providing access to services operated for the organization */
		public array $endpoint = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIROrganizationQualification> qualification Qualifications, certifications, accreditations, licenses, training, etc. pertaining to the provision of care */
		public array $qualification = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

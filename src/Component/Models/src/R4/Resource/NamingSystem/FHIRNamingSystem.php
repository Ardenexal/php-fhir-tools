<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/NamingSystem
 * @description A curated namespace that issues unique symbols within that namespace for the identification of concepts, people, devices, etc.  Represents a "System" used within the Identifier and Coding data types.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'NamingSystem', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/NamingSystem', fhirVersion: 'R4')]
class FHIRNamingSystem extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name Name for this naming system (computer friendly) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNamingSystemTypeType kind codesystem | identifier | root */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRNamingSystemTypeType $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime date Date last changed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string publisher Name of the publisher (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string responsible Who maintains system namespace? */
		public FHIRString|string|null $responsible = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type e.g. driver,  provider,  patient, bank etc. */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown description Natural language description of the naming system */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for naming system (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string usage How/where is it used */
		public FHIRString|string|null $usage = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNamingSystemUniqueId> uniqueId Unique identifiers used for system */
		public array $uniqueId = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

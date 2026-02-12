<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/CatalogEntry
 * @description Catalog entries are wrappers that contextualize items included in a catalog.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'CatalogEntry', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/CatalogEntry', fhirVersion: 'R4')]
class CatalogEntryResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Unique identifier of the catalog item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type The type of item - medication, device, service, protocol or other */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|bool orderable Whether the entry represents an orderable item */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $orderable = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference referencedItem The item that is being defined */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $referencedItem = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> additionalIdentifier Any additional identifier(s) for the catalog item, in the same granularity or concept */
		public array $additionalIdentifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> classification Classification (category or class) of the item entry */
		public array $classification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType status draft | active | retired | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period validityPeriod The time period in which this catalog entry is expected to be active */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $validityPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive validTo The date until which this catalog entry is expected to be active */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $validTo = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive lastUpdated When was this catalog last updated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $lastUpdated = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> additionalCharacteristic Additional characteristics of the catalog entry */
		public array $additionalCharacteristic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> additionalClassification Additional classification of the catalog entry */
		public array $additionalClassification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CatalogEntry\CatalogEntryRelatedEntry> relatedEntry An item that this catalog entry is related to */
		public array $relatedEntry = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

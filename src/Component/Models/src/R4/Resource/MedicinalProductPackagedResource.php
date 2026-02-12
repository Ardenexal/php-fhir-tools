<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductPackaged
 * @description A medicinal product in a container or package.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProductPackaged',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductPackaged',
	fhirVersion: 'R4',
)]
class MedicinalProductPackagedResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Unique identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> subject The product with this is a pack for */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Textual description */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $legalStatusOfSupply = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\MarketingStatus> marketingStatus Marketing information */
		public array $marketingStatus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference marketingAuthorization Manufacturer of this Package Item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $marketingAuthorization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> manufacturer Manufacturer of this Package Item */
		public array $manufacturer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged\MedicinalProductPackagedBatchIdentifier> batchIdentifier Batch numbering */
		public array $batchIdentifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged\MedicinalProductPackagedPackageItem> packageItem A packaging item, as a contained for medicine, possibly with other packaging items within */
		public array $packageItem = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

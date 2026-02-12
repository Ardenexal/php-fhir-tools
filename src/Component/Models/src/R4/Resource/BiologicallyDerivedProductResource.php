<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct
 * @description A material substance originating from a biological entity intended to be transplanted or infused
 * into another (possibly the same) biological entity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'BiologicallyDerivedProduct',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct',
	fhirVersion: 'R4',
)]
class BiologicallyDerivedProductResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier External ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\BiologicallyDerivedProductCategoryType productCategory organ | tissue | fluid | cells | biologicalAgent */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\BiologicallyDerivedProductCategoryType $productCategory = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept productCode What this biologically derived product is */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $productCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\BiologicallyDerivedProductStatusType status available | unavailable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\BiologicallyDerivedProductStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> request Procedure request */
		public array $request = [],
		/** @var null|int quantity The amount of this biologically derived product */
		public ?int $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> parent BiologicallyDerivedProduct parent */
		public array $parent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductCollection collection How this product was collected */
		public ?BiologicallyDerivedProduct\BiologicallyDerivedProductCollection $collection = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductProcessing> processing Any processing of the product during collection */
		public array $processing = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductManipulation manipulation Any manipulation of product post-collection */
		public ?BiologicallyDerivedProduct\BiologicallyDerivedProductManipulation $manipulation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductStorage> storage Product storage */
		public array $storage = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

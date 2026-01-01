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
class FHIRBiologicallyDerivedProduct extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier External ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBiologicallyDerivedProductCategoryType productCategory organ | tissue | fluid | cells | biologicalAgent */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBiologicallyDerivedProductCategoryType $productCategory = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept productCode What this biologically derived product is */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $productCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBiologicallyDerivedProductStatusType status available | unavailable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBiologicallyDerivedProductStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> request Procedure request */
		public array $request = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger quantity The amount of this biologically derived product */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> parent BiologicallyDerivedProduct parent */
		public array $parent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBiologicallyDerivedProductCollection collection How this product was collected */
		public ?FHIRBiologicallyDerivedProductCollection $collection = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBiologicallyDerivedProductProcessing> processing Any processing of the product during collection */
		public array $processing = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBiologicallyDerivedProductManipulation manipulation Any manipulation of product post-collection */
		public ?FHIRBiologicallyDerivedProductManipulation $manipulation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBiologicallyDerivedProductStorage> storage Product storage */
		public array $storage = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct
 * @description This resource reflects an instance of a biologically derived product. A material substance originating from a biological entity intended to be transplanted or infused
 * into another (possibly the same) biological entity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'BiologicallyDerivedProduct',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct',
	fhirVersion: 'R5',
)]
class FHIRBiologicallyDerivedProduct extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding productCategory organ | tissue | fluid | cells | biologicalAgent */
		public ?FHIRCoding $productCategory = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept productCode A code that identifies the kind of this biologically derived product */
		public ?FHIRCodeableConcept $productCode = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> parent The parent biologically-derived product */
		public array $parent = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> request Request to obtain and/or infuse this product */
		public array $request = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier biologicalSourceEvent An identifier that supports traceability to the event during which material in this product from one or more biological entities was obtained or pooled */
		public ?FHIRIdentifier $biologicalSourceEvent = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> processingFacility Processing facilities responsible for the labeling and distribution of this biologically derived product */
		public array $processingFacility = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string division A unique identifier for an aliquot of a product */
		public FHIRString|string|null $division = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding productStatus available | unavailable */
		public ?FHIRCoding $productStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime expirationDate Date, and where relevant time, of expiration */
		public ?FHIRDateTime $expirationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBiologicallyDerivedProductCollection collection How this product was collected */
		public ?FHIRBiologicallyDerivedProductCollection $collection = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange storageTempRequirements Product storage temperature requirements */
		public ?FHIRRange $storageTempRequirements = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBiologicallyDerivedProductProperty> property A property that is specific to this BiologicallyDerviedProduct instance */
		public array $property = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

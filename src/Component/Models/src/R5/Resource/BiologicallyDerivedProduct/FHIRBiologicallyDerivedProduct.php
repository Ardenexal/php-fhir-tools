<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct
 *
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
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null productCategory organ | tissue | fluid | cells | biologicalAgent */
        public ?\FHIRCoding $productCategory = null,
        /** @var FHIRCodeableConcept|null productCode A code that identifies the kind of this biologically derived product */
        public ?\FHIRCodeableConcept $productCode = null,
        /** @var array<FHIRReference> parent The parent biologically-derived product */
        public array $parent = [],
        /** @var array<FHIRReference> request Request to obtain and/or infuse this product */
        public array $request = [],
        /** @var array<FHIRIdentifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var FHIRIdentifier|null biologicalSourceEvent An identifier that supports traceability to the event during which material in this product from one or more biological entities was obtained or pooled */
        public ?\FHIRIdentifier $biologicalSourceEvent = null,
        /** @var array<FHIRReference> processingFacility Processing facilities responsible for the labeling and distribution of this biologically derived product */
        public array $processingFacility = [],
        /** @var FHIRString|string|null division A unique identifier for an aliquot of a product */
        public \FHIRString|string|null $division = null,
        /** @var FHIRCoding|null productStatus available | unavailable */
        public ?\FHIRCoding $productStatus = null,
        /** @var FHIRDateTime|null expirationDate Date, and where relevant time, of expiration */
        public ?\FHIRDateTime $expirationDate = null,
        /** @var FHIRBiologicallyDerivedProductCollection|null collection How this product was collected */
        public ?\FHIRBiologicallyDerivedProductCollection $collection = null,
        /** @var FHIRRange|null storageTempRequirements Product storage temperature requirements */
        public ?\FHIRRange $storageTempRequirements = null,
        /** @var array<FHIRBiologicallyDerivedProductProperty> property A property that is specific to this BiologicallyDerviedProduct instance */
        public array $property = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

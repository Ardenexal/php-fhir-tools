<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProduct
 *
 * @description Detailed definition of a medicinal product, typically for uses other than direct patient care (e.g. regulatory use).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MedicinalProduct',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProduct',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProduct extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for this product. Could be an MPID */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type Regulatory type, e.g. Investigational or Authorized */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCoding|null domain If this medicine applies to human or veterinary uses */
        public ?\FHIRCoding $domain = null,
        /** @var FHIRCodeableConcept|null combinedPharmaceuticalDoseForm The dose form for a single part product, or combined form of a multiple part product */
        public ?\FHIRCodeableConcept $combinedPharmaceuticalDoseForm = null,
        /** @var FHIRCodeableConcept|null legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
        public ?\FHIRCodeableConcept $legalStatusOfSupply = null,
        /** @var FHIRCodeableConcept|null additionalMonitoringIndicator Whether the Medicinal Product is subject to additional monitoring for regulatory reasons */
        public ?\FHIRCodeableConcept $additionalMonitoringIndicator = null,
        /** @var array<FHIRString|string> specialMeasures Whether the Medicinal Product is subject to special measures for regulatory reasons */
        public array $specialMeasures = [],
        /** @var FHIRCodeableConcept|null paediatricUseIndicator If authorised for use in children */
        public ?\FHIRCodeableConcept $paediatricUseIndicator = null,
        /** @var array<FHIRCodeableConcept> productClassification Allows the product to be classified by various systems */
        public array $productClassification = [],
        /** @var array<FHIRMarketingStatus> marketingStatus Marketing status of the medicinal product, in contrast to marketing authorizaton */
        public array $marketingStatus = [],
        /** @var array<FHIRReference> pharmaceuticalProduct Pharmaceutical aspects of product */
        public array $pharmaceuticalProduct = [],
        /** @var array<FHIRReference> packagedMedicinalProduct Package representation for the product */
        public array $packagedMedicinalProduct = [],
        /** @var array<FHIRReference> attachedDocument Supporting documentation, typically for regulatory submission */
        public array $attachedDocument = [],
        /** @var array<FHIRReference> masterFile A master file for to the medicinal product (e.g. Pharmacovigilance System Master File) */
        public array $masterFile = [],
        /** @var array<FHIRReference> contact A product specific contact, person (in a role), or an organization */
        public array $contact = [],
        /** @var array<FHIRReference> clinicalTrial Clinical trials or studies that this product is involved in */
        public array $clinicalTrial = [],
        /** @var array<FHIRMedicinalProductName> name The product's name, including full name and possibly coded parts */
        public array $name = [],
        /** @var array<FHIRIdentifier> crossReference Reference to another product, e.g. for linking authorised to investigational product */
        public array $crossReference = [],
        /** @var array<FHIRMedicinalProductManufacturingBusinessOperation> manufacturingBusinessOperation An operation applied to the product, for manufacturing or adminsitrative purpose */
        public array $manufacturingBusinessOperation = [],
        /** @var array<FHIRMedicinalProductSpecialDesignation> specialDesignation Indicates if the medicinal product has an orphan designation for the treatment of a rare disease */
        public array $specialDesignation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

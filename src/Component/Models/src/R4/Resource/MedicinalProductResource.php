<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MarketingStatus;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductManufacturingBusinessOperation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductName;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductSpecialDesignation;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProduct
 *
 * @description Detailed definition of a medicinal product, typically for uses other than direct patient care (e.g. regulatory use).
 */
#[FhirResource(
    type: 'MedicinalProduct',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProduct',
    fhirVersion: 'R4',
)]
class MedicinalProductResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business identifier for this product. Could be an MPID */
        public array $identifier = [],
        /** @var CodeableConcept|null type Regulatory type, e.g. Investigational or Authorized */
        public ?CodeableConcept $type = null,
        /** @var Coding|null domain If this medicine applies to human or veterinary uses */
        public ?Coding $domain = null,
        /** @var CodeableConcept|null combinedPharmaceuticalDoseForm The dose form for a single part product, or combined form of a multiple part product */
        public ?CodeableConcept $combinedPharmaceuticalDoseForm = null,
        /** @var CodeableConcept|null legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
        public ?CodeableConcept $legalStatusOfSupply = null,
        /** @var CodeableConcept|null additionalMonitoringIndicator Whether the Medicinal Product is subject to additional monitoring for regulatory reasons */
        public ?CodeableConcept $additionalMonitoringIndicator = null,
        /** @var array<StringPrimitive|string> specialMeasures Whether the Medicinal Product is subject to special measures for regulatory reasons */
        public array $specialMeasures = [],
        /** @var CodeableConcept|null paediatricUseIndicator If authorised for use in children */
        public ?CodeableConcept $paediatricUseIndicator = null,
        /** @var array<CodeableConcept> productClassification Allows the product to be classified by various systems */
        public array $productClassification = [],
        /** @var array<MarketingStatus> marketingStatus Marketing status of the medicinal product, in contrast to marketing authorizaton */
        public array $marketingStatus = [],
        /** @var array<Reference> pharmaceuticalProduct Pharmaceutical aspects of product */
        public array $pharmaceuticalProduct = [],
        /** @var array<Reference> packagedMedicinalProduct Package representation for the product */
        public array $packagedMedicinalProduct = [],
        /** @var array<Reference> attachedDocument Supporting documentation, typically for regulatory submission */
        public array $attachedDocument = [],
        /** @var array<Reference> masterFile A master file for to the medicinal product (e.g. Pharmacovigilance System Master File) */
        public array $masterFile = [],
        /** @var array<Reference> contact A product specific contact, person (in a role), or an organization */
        public array $contact = [],
        /** @var array<Reference> clinicalTrial Clinical trials or studies that this product is involved in */
        public array $clinicalTrial = [],
        /** @var array<MedicinalProductName> name The product's name, including full name and possibly coded parts */
        public array $name = [],
        /** @var array<Identifier> crossReference Reference to another product, e.g. for linking authorised to investigational product */
        public array $crossReference = [],
        /** @var array<MedicinalProductManufacturingBusinessOperation> manufacturingBusinessOperation An operation applied to the product, for manufacturing or adminsitrative purpose */
        public array $manufacturingBusinessOperation = [],
        /** @var array<MedicinalProductSpecialDesignation> specialDesignation Indicates if the medicinal product has an orphan designation for the treatment of a rare disease */
        public array $specialDesignation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

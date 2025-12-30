<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMarketingStatus;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductDefinition
 *
 * @description A medicinal product, being a substance or combination of substances that is intended to treat, prevent or diagnose a disease, or to restore, correct or modify physiological functions by exerting a pharmacological, immunological or metabolic action. This resource is intended to define and detail such products and their properties, for uses other than direct patient care (e.g. regulatory use, or drug catalogs).
 */
#[FhirResource(
    type: 'MedicinalProductDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductDefinition',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for this product. Could be an MPID */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type Regulatory type, e.g. Investigational or Authorized */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null domain If this medicine applies to human or veterinary uses */
        public ?FHIRCodeableConcept $domain = null,
        /** @var FHIRString|string|null version A business identifier relating to a specific version of the product */
        public FHIRString|string|null $version = null,
        /** @var FHIRCodeableConcept|null status The status within the lifecycle of this product record */
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRDateTime|null statusDate The date at which the given status became applicable */
        public ?FHIRDateTime $statusDate = null,
        /** @var FHIRMarkdown|null description General description of this product */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRCodeableConcept|null combinedPharmaceuticalDoseForm The dose form for a single part product, or combined form of a multiple part product */
        public ?FHIRCodeableConcept $combinedPharmaceuticalDoseForm = null,
        /** @var array<FHIRCodeableConcept> route The path by which the product is taken into or makes contact with the body */
        public array $route = [],
        /** @var FHIRMarkdown|null indication Description of indication(s) for this product, used when structured indications are not required */
        public ?FHIRMarkdown $indication = null,
        /** @var FHIRCodeableConcept|null legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
        public ?FHIRCodeableConcept $legalStatusOfSupply = null,
        /** @var FHIRCodeableConcept|null additionalMonitoringIndicator Whether the Medicinal Product is subject to additional monitoring for regulatory reasons */
        public ?FHIRCodeableConcept $additionalMonitoringIndicator = null,
        /** @var array<FHIRCodeableConcept> specialMeasures Whether the Medicinal Product is subject to special measures for regulatory reasons */
        public array $specialMeasures = [],
        /** @var FHIRCodeableConcept|null pediatricUseIndicator If authorised for use in children */
        public ?FHIRCodeableConcept $pediatricUseIndicator = null,
        /** @var array<FHIRCodeableConcept> classification Allows the product to be classified by various systems */
        public array $classification = [],
        /** @var array<FHIRMarketingStatus> marketingStatus Marketing status of the medicinal product, in contrast to marketing authorization */
        public array $marketingStatus = [],
        /** @var array<FHIRCodeableConcept> packagedMedicinalProduct Package type for the product */
        public array $packagedMedicinalProduct = [],
        /** @var array<FHIRReference> comprisedOf Types of medicinal manufactured items and/or devices that this product consists of, such as tablets, capsule, or syringes */
        public array $comprisedOf = [],
        /** @var array<FHIRCodeableConcept> ingredient The ingredients of this medicinal product - when not detailed in other resources */
        public array $ingredient = [],
        /** @var array<FHIRCodeableReference> impurity Any component of the drug product which is not the chemical entity defined as the drug substance, or an excipient in the drug product */
        public array $impurity = [],
        /** @var array<FHIRReference> attachedDocument Additional documentation about the medicinal product */
        public array $attachedDocument = [],
        /** @var array<FHIRReference> masterFile A master file for the medicinal product (e.g. Pharmacovigilance System Master File) */
        public array $masterFile = [],
        /** @var array<FHIRMedicinalProductDefinitionContact> contact A product specific contact, person (in a role), or an organization */
        public array $contact = [],
        /** @var array<FHIRReference> clinicalTrial Clinical trials or studies that this product is involved in */
        public array $clinicalTrial = [],
        /** @var array<FHIRCoding> code A code that this product is known by, within some formal terminology */
        public array $code = [],
        /** @var array<FHIRMedicinalProductDefinitionName> name The product's name, including full name and possibly coded parts */
        public array $name = [],
        /** @var array<FHIRMedicinalProductDefinitionCrossReference> crossReference Reference to another product, e.g. for linking authorised to investigational product */
        public array $crossReference = [],
        /** @var array<FHIRMedicinalProductDefinitionOperation> operation A manufacturing or administrative process for the medicinal product */
        public array $operation = [],
        /** @var array<FHIRMedicinalProductDefinitionCharacteristic> characteristic Key product features such as "sugar free", "modified release" */
        public array $characteristic = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductPackaged
 *
 * @description A medicinal product in a container or package.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MedicinalProductPackaged',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductPackaged',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductPackaged extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Unique identifier */
        public array $identifier = [],
        /** @var array<FHIRReference> subject The product with this is a pack for */
        public array $subject = [],
        /** @var FHIRString|string|null description Textual description */
        public \FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
        public ?\FHIRCodeableConcept $legalStatusOfSupply = null,
        /** @var array<FHIRMarketingStatus> marketingStatus Marketing information */
        public array $marketingStatus = [],
        /** @var FHIRReference|null marketingAuthorization Manufacturer of this Package Item */
        public ?\FHIRReference $marketingAuthorization = null,
        /** @var array<FHIRReference> manufacturer Manufacturer of this Package Item */
        public array $manufacturer = [],
        /** @var array<FHIRMedicinalProductPackagedBatchIdentifier> batchIdentifier Batch numbering */
        public array $batchIdentifier = [],
        /** @var array<FHIRMedicinalProductPackagedPackageItem> packageItem A packaging item, as a contained for medicine, possibly with other packaging items within */
        public array $packageItem = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMarketingStatus;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PackagedProductDefinition
 *
 * @description A medically related item or items, in a container or package.
 */
#[FhirResource(
    type: 'PackagedProductDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/PackagedProductDefinition',
    fhirVersion: 'R5',
)]
class FHIRPackagedProductDefinition extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier A unique identifier for this package as whole - not for the content of the package */
        public array $identifier = [],
        /** @var FHIRString|string|null name A name for this package. Typically as listed in a drug formulary, catalogue, inventory etc */
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null type A high level category e.g. medicinal product, raw material, shipping container etc */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRReference> packageFor The product that this is a pack for */
        public array $packageFor = [],
        /** @var FHIRCodeableConcept|null status The status within the lifecycle of this item. High level - not intended to duplicate details elsewhere e.g. legal status, or authorization/marketing status */
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRDateTime|null statusDate The date at which the given status became applicable */
        public ?FHIRDateTime $statusDate = null,
        /** @var array<FHIRQuantity> containedItemQuantity A total of the complete count of contained items of a particular type/form, independent of sub-packaging or organization. This can be considered as the pack size. See also packaging.containedItem.amount (especially the long definition) */
        public array $containedItemQuantity = [],
        /** @var FHIRMarkdown|null description Textual description. Note that this is not the name of the package or product */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRPackagedProductDefinitionLegalStatusOfSupply> legalStatusOfSupply The legal status of supply of the packaged item as classified by the regulator */
        public array $legalStatusOfSupply = [],
        /** @var array<FHIRMarketingStatus> marketingStatus Allows specifying that an item is on the market for sale, or that it is not available, and the dates and locations associated */
        public array $marketingStatus = [],
        /** @var FHIRBoolean|null copackagedIndicator Identifies if the drug product is supplied with another item such as a diluent or adjuvant */
        public ?FHIRBoolean $copackagedIndicator = null,
        /** @var array<FHIRReference> manufacturer Manufacturer of this package type (multiple means these are all possible manufacturers) */
        public array $manufacturer = [],
        /** @var array<FHIRReference> attachedDocument Additional information or supporting documentation about the packaged product */
        public array $attachedDocument = [],
        /** @var FHIRPackagedProductDefinitionPackaging|null packaging A packaging item, as a container for medically related items, possibly with other packaging items within, or a packaging component, such as bottle cap */
        public ?FHIRPackagedProductDefinitionPackaging $packaging = null,
        /** @var array<FHIRPackagedProductDefinitionPackagingProperty> characteristic Allows the key features to be recorded, such as "hospital pack", "nurse prescribable" */
        public array $characteristic = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMarketingStatus;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ManufacturedItemDefinition
 *
 * @description The definition and characteristics of a medicinal manufactured item, such as a tablet or capsule, as contained in a packaged medicinal product.
 */
#[FhirResource(
    type: 'ManufacturedItemDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ManufacturedItemDefinition',
    fhirVersion: 'R5',
)]
class FHIRManufacturedItemDefinition extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Unique identifier */
        public array $identifier = [],
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRString|string|null name A descriptive name applied to this item */
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null manufacturedDoseForm Dose form as manufactured (before any necessary transformation) */
        #[NotBlank]
        public ?FHIRCodeableConcept $manufacturedDoseForm = null,
        /** @var FHIRCodeableConcept|null unitOfPresentation The “real-world” units in which the quantity of the item is described */
        public ?FHIRCodeableConcept $unitOfPresentation = null,
        /** @var array<FHIRReference> manufacturer Manufacturer of the item, one of several possible */
        public array $manufacturer = [],
        /** @var array<FHIRMarketingStatus> marketingStatus Allows specifying that an item is on the market for sale, or that it is not available, and the dates and locations associated */
        public array $marketingStatus = [],
        /** @var array<FHIRCodeableConcept> ingredient The ingredients of this manufactured item. Only needed if these are not specified by incoming references from the Ingredient resource */
        public array $ingredient = [],
        /** @var array<FHIRManufacturedItemDefinitionProperty> property General characteristics of this item */
        public array $property = [],
        /** @var array<FHIRManufacturedItemDefinitionComponent> component Physical parts of the manufactured item, that it is intrisically made from. This is distinct from the ingredients that are part of its chemical makeup */
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

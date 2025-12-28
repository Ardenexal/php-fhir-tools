<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CatalogEntry
 *
 * @description Catalog entries are wrappers that contextualize items included in a catalog.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'CatalogEntry', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/CatalogEntry', fhirVersion: 'R4B')]
class FHIRCatalogEntry extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Unique identifier of the catalog item */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type The type of item - medication, device, service, protocol or other */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRBoolean|null orderable Whether the entry represents an orderable item */
        #[NotBlank]
        public ?\FHIRBoolean $orderable = null,
        /** @var FHIRReference|null referencedItem The item that is being defined */
        #[NotBlank]
        public ?\FHIRReference $referencedItem = null,
        /** @var array<FHIRIdentifier> additionalIdentifier Any additional identifier(s) for the catalog item, in the same granularity or concept */
        public array $additionalIdentifier = [],
        /** @var array<FHIRCodeableConcept> classification Classification (category or class) of the item entry */
        public array $classification = [],
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRPeriod|null validityPeriod The time period in which this catalog entry is expected to be active */
        public ?\FHIRPeriod $validityPeriod = null,
        /** @var FHIRDateTime|null validTo The date until which this catalog entry is expected to be active */
        public ?\FHIRDateTime $validTo = null,
        /** @var FHIRDateTime|null lastUpdated When was this catalog last updated */
        public ?\FHIRDateTime $lastUpdated = null,
        /** @var array<FHIRCodeableConcept> additionalCharacteristic Additional characteristics of the catalog entry */
        public array $additionalCharacteristic = [],
        /** @var array<FHIRCodeableConcept> additionalClassification Additional classification of the catalog entry */
        public array $additionalClassification = [],
        /** @var array<FHIRCatalogEntryRelatedEntry> relatedEntry An item that this catalog entry is related to */
        public array $relatedEntry = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

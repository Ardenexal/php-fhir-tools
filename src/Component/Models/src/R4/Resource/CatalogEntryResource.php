<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CatalogEntry\CatalogEntryRelatedEntry;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CatalogEntry
 *
 * @description Catalog entries are wrappers that contextualize items included in a catalog.
 */
#[FhirResource(type: 'CatalogEntry', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/CatalogEntry', fhirVersion: 'R4')]
class CatalogEntryResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Unique identifier of the catalog item */
        public array $identifier = [],
        /** @var CodeableConcept|null type The type of item - medication, device, service, protocol or other */
        public ?CodeableConcept $type = null,
        /** @var bool|null orderable Whether the entry represents an orderable item */
        #[NotBlank]
        public ?bool $orderable = null,
        /** @var Reference|null referencedItem The item that is being defined */
        #[NotBlank]
        public ?Reference $referencedItem = null,
        /** @var array<Identifier> additionalIdentifier Any additional identifier(s) for the catalog item, in the same granularity or concept */
        public array $additionalIdentifier = [],
        /** @var array<CodeableConcept> classification Classification (category or class) of the item entry */
        public array $classification = [],
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        public ?PublicationStatusType $status = null,
        /** @var Period|null validityPeriod The time period in which this catalog entry is expected to be active */
        public ?Period $validityPeriod = null,
        /** @var DateTimePrimitive|null validTo The date until which this catalog entry is expected to be active */
        public ?DateTimePrimitive $validTo = null,
        /** @var DateTimePrimitive|null lastUpdated When was this catalog last updated */
        public ?DateTimePrimitive $lastUpdated = null,
        /** @var array<CodeableConcept> additionalCharacteristic Additional characteristics of the catalog entry */
        public array $additionalCharacteristic = [],
        /** @var array<CodeableConcept> additionalClassification Additional classification of the catalog entry */
        public array $additionalClassification = [],
        /** @var array<CatalogEntryRelatedEntry> relatedEntry An item that this catalog entry is related to */
        public array $relatedEntry = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MarketingStatus;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged\MedicinalProductPackagedBatchIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged\MedicinalProductPackagedPackageItem;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductPackaged
 *
 * @description A medicinal product in a container or package.
 */
#[FhirResource(
    type: 'MedicinalProductPackaged',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductPackaged',
    fhirVersion: 'R4',
)]
class MedicinalProductPackagedResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Unique identifier */
        public array $identifier = [],
        /** @var array<Reference> subject The product with this is a pack for */
        public array $subject = [],
        /** @var StringPrimitive|string|null description Textual description */
        public StringPrimitive|string|null $description = null,
        /** @var CodeableConcept|null legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
        public ?CodeableConcept $legalStatusOfSupply = null,
        /** @var array<MarketingStatus> marketingStatus Marketing information */
        public array $marketingStatus = [],
        /** @var Reference|null marketingAuthorization Manufacturer of this Package Item */
        public ?Reference $marketingAuthorization = null,
        /** @var array<Reference> manufacturer Manufacturer of this Package Item */
        public array $manufacturer = [],
        /** @var array<MedicinalProductPackagedBatchIdentifier> batchIdentifier Batch numbering */
        public array $batchIdentifier = [],
        /** @var array<MedicinalProductPackagedPackageItem> packageItem A packaging item, as a contained for medicine, possibly with other packaging items within */
        public array $packageItem = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

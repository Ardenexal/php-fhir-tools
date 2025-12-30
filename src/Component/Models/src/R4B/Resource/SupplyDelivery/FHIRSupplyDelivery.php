<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSupplyDeliveryStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SupplyDelivery
 *
 * @description Record of delivery of what is supplied.
 */
#[FhirResource(
    type: 'SupplyDelivery',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SupplyDelivery',
    fhirVersion: 'R4B',
)]
class FHIRSupplyDelivery extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIRSupplyDeliveryStatusType|null status in-progress | completed | abandoned | entered-in-error */
        public ?FHIRSupplyDeliveryStatusType $status = null,
        /** @var FHIRReference|null patient Patient for whom the item is supplied */
        public ?FHIRReference $patient = null,
        /** @var FHIRCodeableConcept|null type Category of dispense event */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRSupplyDeliverySuppliedItem|null suppliedItem The item that is delivered or supplied */
        public ?FHIRSupplyDeliverySuppliedItem $suppliedItem = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX When event occurred */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
        /** @var FHIRReference|null supplier Dispenser */
        public ?FHIRReference $supplier = null,
        /** @var FHIRReference|null destination Where the Supply was sent */
        public ?FHIRReference $destination = null,
        /** @var array<FHIRReference> receiver Who collected the Supply */
        public array $receiver = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

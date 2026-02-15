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
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SupplyDeliveryStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SupplyDelivery\SupplyDeliverySuppliedItem;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SupplyDelivery
 *
 * @description Record of delivery of what is supplied.
 */
#[FhirResource(
    type: 'SupplyDelivery',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SupplyDelivery',
    fhirVersion: 'R4',
)]
class SupplyDeliveryResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External identifier */
        public array $identifier = [],
        /** @var array<Reference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var SupplyDeliveryStatusType|null status in-progress | completed | abandoned | entered-in-error */
        public ?SupplyDeliveryStatusType $status = null,
        /** @var Reference|null patient Patient for whom the item is supplied */
        public ?Reference $patient = null,
        /** @var CodeableConcept|null type Category of dispense event */
        public ?CodeableConcept $type = null,
        /** @var SupplyDeliverySuppliedItem|null suppliedItem The item that is delivered or supplied */
        public ?SupplyDeliverySuppliedItem $suppliedItem = null,
        /** @var DateTimePrimitive|Period|Timing|null occurrenceX When event occurred */
        public DateTimePrimitive|Period|Timing|null $occurrenceX = null,
        /** @var Reference|null supplier Dispenser */
        public ?Reference $supplier = null,
        /** @var Reference|null destination Where the Supply was sent */
        public ?Reference $destination = null,
        /** @var array<Reference> receiver Who collected the Supply */
        public array $receiver = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

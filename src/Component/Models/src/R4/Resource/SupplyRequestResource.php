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
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SupplyRequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SupplyRequest\SupplyRequestParameter;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SupplyRequest
 *
 * @description A record of a request for a medication, substance or device used in the healthcare setting.
 */
#[FhirResource(type: 'SupplyRequest', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/SupplyRequest', fhirVersion: 'R4')]
class SupplyRequestResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for SupplyRequest */
        public array $identifier = [],
        /** @var SupplyRequestStatusType|null status draft | active | suspended + */
        public ?SupplyRequestStatusType $status = null,
        /** @var CodeableConcept|null category The kind of supply (central, non-stock, etc.) */
        public ?CodeableConcept $category = null,
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var CodeableConcept|Reference|null itemX Medication, Substance, or Device requested to be supplied */
        #[NotBlank]
        public CodeableConcept|Reference|null $itemX = null,
        /** @var Quantity|null quantity The requested amount of the item indicated */
        #[NotBlank]
        public ?Quantity $quantity = null,
        /** @var array<SupplyRequestParameter> parameter Ordered item details */
        public array $parameter = [],
        /** @var DateTimePrimitive|Period|Timing|null occurrenceX When the request should be fulfilled */
        public DateTimePrimitive|Period|Timing|null $occurrenceX = null,
        /** @var DateTimePrimitive|null authoredOn When the request was made */
        public ?DateTimePrimitive $authoredOn = null,
        /** @var Reference|null requester Individual making the request */
        public ?Reference $requester = null,
        /** @var array<Reference> supplier Who is intended to fulfill the request */
        public array $supplier = [],
        /** @var array<CodeableConcept> reasonCode The reason why the supply item was requested */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference The reason why the supply item was requested */
        public array $reasonReference = [],
        /** @var Reference|null deliverFrom The origin of the supply */
        public ?Reference $deliverFrom = null,
        /** @var Reference|null deliverTo The destination of the supply */
        public ?Reference $deliverTo = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

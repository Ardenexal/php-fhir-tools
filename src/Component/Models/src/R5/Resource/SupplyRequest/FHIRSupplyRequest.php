<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSupplyRequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SupplyRequest
 *
 * @description A record of a request to deliver a medication, substance or device used in the healthcare setting to a particular destination for a particular person or organization.
 */
#[FhirResource(type: 'SupplyRequest', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/SupplyRequest', fhirVersion: 'R5')]
class FHIRSupplyRequest extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for SupplyRequest */
        public array $identifier = [],
        /** @var FHIRSupplyRequestStatusType|null status draft | active | suspended + */
        public ?FHIRSupplyRequestStatusType $status = null,
        /** @var array<FHIRReference> basedOn What other request is fulfilled by this supply request */
        public array $basedOn = [],
        /** @var FHIRCodeableConcept|null category The kind of supply (central, non-stock, etc.) */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRReference|null deliverFor The patient for who the supply request is for */
        public ?FHIRReference $deliverFor = null,
        /** @var FHIRCodeableReference|null item Medication, Substance, or Device requested to be supplied */
        #[NotBlank]
        public ?FHIRCodeableReference $item = null,
        /** @var FHIRQuantity|null quantity The requested amount of the item indicated */
        #[NotBlank]
        public ?FHIRQuantity $quantity = null,
        /** @var array<FHIRSupplyRequestParameter> parameter Ordered item details */
        public array $parameter = [],
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX When the request should be fulfilled */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
        /** @var FHIRDateTime|null authoredOn When the request was made */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Individual making the request */
        public ?FHIRReference $requester = null,
        /** @var array<FHIRReference> supplier Who is intended to fulfill the request */
        public array $supplier = [],
        /** @var array<FHIRCodeableReference> reason The reason why the supply item was requested */
        public array $reason = [],
        /** @var FHIRReference|null deliverFrom The origin of the supply */
        public ?FHIRReference $deliverFrom = null,
        /** @var FHIRReference|null deliverTo The destination of the supply */
        public ?FHIRReference $deliverTo = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

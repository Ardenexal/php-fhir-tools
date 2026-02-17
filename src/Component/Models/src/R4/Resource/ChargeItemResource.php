<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ChargeItemStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ChargeItem\ChargeItemPerformer;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ChargeItem
 *
 * @description The resource ChargeItem describes the provision of healthcare provider products for a certain patient, therefore referring not only to the product, but containing in addition details of the provision, like date, time, amounts and participating organizations and persons. Main Usage of the ChargeItem is to enable the billing process and internal cost allocation.
 */
#[FhirResource(type: 'ChargeItem', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ChargeItem', fhirVersion: 'R4')]
class ChargeItemResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for item */
        public array $identifier = [],
        /** @var array<UriPrimitive> definitionUri Defining information about the code of this charge item */
        public array $definitionUri = [],
        /** @var array<CanonicalPrimitive> definitionCanonical Resource defining the code of this ChargeItem */
        public array $definitionCanonical = [],
        /** @var ChargeItemStatusType|null status planned | billable | not-billable | aborted | billed | entered-in-error | unknown */
        #[NotBlank]
        public ?ChargeItemStatusType $status = null,
        /** @var array<Reference> partOf Part of referenced ChargeItem */
        public array $partOf = [],
        /** @var CodeableConcept|null code A code that identifies the charge, like a billing code */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Individual service was done for/to */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null context Encounter / Episode associated with event */
        public ?Reference $context = null,
        /** @var DateTimePrimitive|Period|Timing|null occurrenceX When the charged service was applied */
        public DateTimePrimitive|Period|Timing|null $occurrenceX = null,
        /** @var array<ChargeItemPerformer> performer Who performed charged service */
        public array $performer = [],
        /** @var Reference|null performingOrganization Organization providing the charged service */
        public ?Reference $performingOrganization = null,
        /** @var Reference|null requestingOrganization Organization requesting the charged service */
        public ?Reference $requestingOrganization = null,
        /** @var Reference|null costCenter Organization that has ownership of the (potential, future) revenue */
        public ?Reference $costCenter = null,
        /** @var Quantity|null quantity Quantity of which the charge item has been serviced */
        public ?Quantity $quantity = null,
        /** @var array<CodeableConcept> bodysite Anatomical location, if relevant */
        public array $bodysite = [],
        /** @var float|null factorOverride Factor overriding the associated rules */
        public ?float $factorOverride = null,
        /** @var Money|null priceOverride Price overriding the associated rules */
        public ?Money $priceOverride = null,
        /** @var StringPrimitive|string|null overrideReason Reason for overriding the list price/factor */
        public StringPrimitive|string|null $overrideReason = null,
        /** @var Reference|null enterer Individual who was entering */
        public ?Reference $enterer = null,
        /** @var DateTimePrimitive|null enteredDate Date the charge item was entered */
        public ?DateTimePrimitive $enteredDate = null,
        /** @var array<CodeableConcept> reason Why was the charged  service rendered? */
        public array $reason = [],
        /** @var array<Reference> service Which rendered service is being charged? */
        public array $service = [],
        /** @var Reference|CodeableConcept|null productX Product charged */
        public Reference|CodeableConcept|null $productX = null,
        /** @var array<Reference> account Account to place this charge */
        public array $account = [],
        /** @var array<Annotation> note Comments made about the ChargeItem */
        public array $note = [],
        /** @var array<Reference> supportingInformation Further information supporting this charge */
        public array $supportingInformation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

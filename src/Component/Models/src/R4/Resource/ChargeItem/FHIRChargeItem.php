<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ChargeItem
 *
 * @description The resource ChargeItem describes the provision of healthcare provider products for a certain patient, therefore referring not only to the product, but containing in addition details of the provision, like date, time, amounts and participating organizations and persons. Main Usage of the ChargeItem is to enable the billing process and internal cost allocation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'ChargeItem', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ChargeItem', fhirVersion: 'R4')]
class FHIRChargeItem extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for item */
        public array $identifier = [],
        /** @var array<FHIRUri> definitionUri Defining information about the code of this charge item */
        public array $definitionUri = [],
        /** @var array<FHIRCanonical> definitionCanonical Resource defining the code of this ChargeItem */
        public array $definitionCanonical = [],
        /** @var FHIRChargeItemStatusType|null status planned | billable | not-billable | aborted | billed | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRChargeItemStatusType $status = null,
        /** @var array<FHIRReference> partOf Part of referenced ChargeItem */
        public array $partOf = [],
        /** @var FHIRCodeableConcept|null code A code that identifies the charge, like a billing code */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Individual service was done for/to */
        #[NotBlank]
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null context Encounter / Episode associated with event */
        public ?\FHIRReference $context = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX When the charged service was applied */
        public \FHIRDateTime|\FHIRPeriod|\FHIRTiming|null $occurrenceX = null,
        /** @var array<FHIRChargeItemPerformer> performer Who performed charged service */
        public array $performer = [],
        /** @var FHIRReference|null performingOrganization Organization providing the charged service */
        public ?\FHIRReference $performingOrganization = null,
        /** @var FHIRReference|null requestingOrganization Organization requesting the charged service */
        public ?\FHIRReference $requestingOrganization = null,
        /** @var FHIRReference|null costCenter Organization that has ownership of the (potential, future) revenue */
        public ?\FHIRReference $costCenter = null,
        /** @var FHIRQuantity|null quantity Quantity of which the charge item has been serviced */
        public ?\FHIRQuantity $quantity = null,
        /** @var array<FHIRCodeableConcept> bodysite Anatomical location, if relevant */
        public array $bodysite = [],
        /** @var FHIRDecimal|null factorOverride Factor overriding the associated rules */
        public ?\FHIRDecimal $factorOverride = null,
        /** @var FHIRMoney|null priceOverride Price overriding the associated rules */
        public ?\FHIRMoney $priceOverride = null,
        /** @var FHIRString|string|null overrideReason Reason for overriding the list price/factor */
        public \FHIRString|string|null $overrideReason = null,
        /** @var FHIRReference|null enterer Individual who was entering */
        public ?\FHIRReference $enterer = null,
        /** @var FHIRDateTime|null enteredDate Date the charge item was entered */
        public ?\FHIRDateTime $enteredDate = null,
        /** @var array<FHIRCodeableConcept> reason Why was the charged  service rendered? */
        public array $reason = [],
        /** @var array<FHIRReference> service Which rendered service is being charged? */
        public array $service = [],
        /** @var FHIRReference|FHIRCodeableConcept|null productX Product charged */
        public \FHIRReference|\FHIRCodeableConcept|null $productX = null,
        /** @var array<FHIRReference> account Account to place this charge */
        public array $account = [],
        /** @var array<FHIRAnnotation> note Comments made about the ChargeItem */
        public array $note = [],
        /** @var array<FHIRReference> supportingInformation Further information supporting this charge */
        public array $supportingInformation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ChargeItemStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ChargeItem\ChargeItemPerformer;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ChargeItem
 *
 * @description The resource ChargeItem describes the provision of healthcare provider products for a certain patient, therefore referring not only to the product, but containing in addition details of the provision, like date, time, amounts and participating organizations and persons. Main Usage of the ChargeItem is to enable the billing process and internal cost allocation.
 */
#[FhirResource(type: 'ChargeItem', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/ChargeItem', fhirVersion: 'R4B')]
class ChargeItemResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business Identifier for item */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var array<UriPrimitive> definitionUri Defining information about the code of this charge item */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
        public array $definitionUri = [],
        /** @var array<CanonicalPrimitive> definitionCanonical Resource defining the code of this ChargeItem */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $definitionCanonical = [],
        /** @var ChargeItemStatusType|null status planned | billable | not-billable | aborted | billed | entered-in-error | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ChargeItemStatusType $status = null,
        /** @var array<Reference> partOf Part of referenced ChargeItem */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $partOf = [],
        /** @var CodeableConcept|null code A code that identifies the charge, like a billing code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Individual service was done for/to */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null context Encounter / Episode associated with event */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $context = null,
        /** @var DateTimePrimitive|Period|Timing|null occurrence When the charged service was applied */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'occurrenceDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'occurrencePeriod',
                ],
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Timing',
                    'jsonKey'      => 'occurrenceTiming',
                ],
            ],
        )]
        public DateTimePrimitive|Period|Timing|null $occurrence = null,
        /** @var array<ChargeItemPerformer> performer Who performed charged service */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ChargeItem\ChargeItemPerformer',
        )]
        public array $performer = [],
        /** @var Reference|null performingOrganization Organization providing the charged service */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $performingOrganization = null,
        /** @var Reference|null requestingOrganization Organization requesting the charged service */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $requestingOrganization = null,
        /** @var Reference|null costCenter Organization that has ownership of the (potential, future) revenue */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $costCenter = null,
        /** @var Quantity|null quantity Quantity of which the charge item has been serviced */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var array<CodeableConcept> bodysite Anatomical location, if relevant */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $bodysite = [],
        /** @var numeric-string|null factorOverride Factor overriding the associated rules */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $factorOverride = null,
        /** @var Money|null priceOverride Price overriding the associated rules */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $priceOverride = null,
        /** @var StringPrimitive|string|null overrideReason Reason for overriding the list price/factor */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $overrideReason = null,
        /** @var Reference|null enterer Individual who was entering */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $enterer = null,
        /** @var DateTimePrimitive|null enteredDate Date the charge item was entered */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $enteredDate = null,
        /** @var array<CodeableConcept> reason Why was the charged  service rendered? */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $reason = [],
        /** @var array<Reference> service Which rendered service is being charged? */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $service = [],
        /** @var Reference|CodeableConcept|null product Product charged */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
                    'jsonKey'      => 'productReference',
                ],
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
                    'jsonKey'      => 'productCodeableConcept',
                ],
            ],
        )]
        public Reference|CodeableConcept|null $product = null,
        /** @var array<Reference> account Account to place this charge */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $account = [],
        /** @var array<Annotation> note Comments made about the ChargeItem */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation',
        )]
        public array $note = [],
        /** @var array<Reference> supportingInformation Further information supporting this charge */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        public array $supportingInformation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

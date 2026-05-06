<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Address;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;

/**
 * @description The first-tier service adjudications for payor added product or service lines.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.addItem', fhirVersion: 'R5')]
class ClaimResponseAddItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<PositiveIntPrimitive> itemSequence Item sequence number */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $itemSequence = [],
        /** @var array<PositiveIntPrimitive> detailSequence Detail sequence number */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $detailSequence = [],
        /** @var array<PositiveIntPrimitive> subdetailSequence Subdetail sequence number */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $subdetailSequence = [],
        /** @var array<Identifier> traceNumber Number for tracking */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $traceNumber = [],
        /** @var array<Reference> provider Authorized providers */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $provider = [],
        /** @var CodeableConcept|null revenue Revenue or cost center code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $revenue = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $productOrService = null,
        /** @var CodeableConcept|null productOrServiceEnd End of a range of codes */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $productOrServiceEnd = null,
        /** @var array<Reference> request Request or Referral for Service */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $request = [],
        /** @var array<CodeableConcept> modifier Service/Product billing modifiers */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $modifier = [],
        /** @var array<CodeableConcept> programCode Program the product or service is provided under */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $programCode = [],
        /** @var DatePrimitive|Period|null serviced Date or dates of service or product delivery */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'date',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive',
                    'jsonKey'      => 'servicedDate',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'servicedPeriod',
                ],
            ],
        )]
        public DatePrimitive|Period|null $serviced = null,
        /** @var CodeableConcept|Address|Reference|null location Place of service or where product was supplied */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
                    'jsonKey'      => 'locationCodeableConcept',
                ],
                [
                    'fhirType'     => 'Address',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Address',
                    'jsonKey'      => 'locationAddress',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'locationReference',
                ],
            ],
        )]
        public CodeableConcept|Address|Reference|null $location = null,
        /** @var Quantity|null quantity Count of products or services */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var Money|null unitPrice Fee, charge or cost per item */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $unitPrice = null,
        /** @var numeric-string|null factor Price scaling factor */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $factor = null,
        /** @var Money|null tax Total tax */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $tax = null,
        /** @var Money|null net Total item cost */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $net = null,
        /** @var array<ClaimResponseAddItemBodySite> bodySite Anatomical location */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse\ClaimResponseAddItemBodySite',
        )]
        public array $bodySite = [],
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $noteNumber = [],
        /** @var ClaimResponseItemReviewOutcome|null reviewOutcome Added items adjudication results */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?ClaimResponseItemReviewOutcome $reviewOutcome = null,
        /** @var array<ClaimResponseItemAdjudication> adjudication Added items adjudication */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse\ClaimResponseItemAdjudication',
        )]
        public array $adjudication = [],
        /** @var array<ClaimResponseAddItemDetail> detail Insurer added line details */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse\ClaimResponseAddItemDetail',
        )]
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;

/**
 * @description The third-tier service adjudications for payor added services.
 */
#[FHIRBackboneElement(parentResource: 'ClaimResponse', elementPath: 'ClaimResponse.addItem.detail.subDetail', fhirVersion: 'R5')]
class ClaimResponseAddItemDetailSubDetail extends BackboneElement
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
        /** @var array<Identifier> traceNumber Number for tracking */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $traceNumber = [],
        /** @var CodeableConcept|null revenue Revenue or cost center code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $revenue = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $productOrService = null,
        /** @var CodeableConcept|null productOrServiceEnd End of a range of codes */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $productOrServiceEnd = null,
        /** @var array<CodeableConcept> modifier Service/Product billing modifiers */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $modifier = [],
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
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $noteNumber = [],
        /** @var ClaimResponseItemReviewOutcome|null reviewOutcome Added items subdetail level adjudication results */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?ClaimResponseItemReviewOutcome $reviewOutcome = null,
        /** @var array<ClaimResponseItemAdjudication> adjudication Added items subdetail adjudication */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClaimResponse\ClaimResponseItemAdjudication',
        )]
        public array $adjudication = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

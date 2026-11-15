<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @description The second-tier service adjudications for payor added services.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.addItem.detail', fhirVersion: 'R5')]
class ExplanationOfBenefitAddItemDetail extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var array<Identifier> traceNumber Number for tracking */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        #[Valid]
        public array $traceNumber = [],
        /** @var CodeableConcept|null revenue Revenue or cost center code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), Valid]
        public ?CodeableConcept $revenue = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), Valid]
        public ?CodeableConcept $productOrService = null,
        /** @var CodeableConcept|null productOrServiceEnd End of a range of codes */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), Valid]
        public ?CodeableConcept $productOrServiceEnd = null,
        /** @var array<CodeableConcept> modifier Service/Product billing modifiers */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[Valid]
        public array $modifier = [],
        /** @var Money|null patientPaid Paid by the patient */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex'), Valid]
        public ?Money $patientPaid = null,
        /** @var Quantity|null quantity Count of products or services */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex'), Valid]
        public ?Quantity $quantity = null,
        /** @var Money|null unitPrice Fee, charge or cost per item */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex'), Valid]
        public ?Money $unitPrice = null,
        /** @var numeric-string|null factor Price scaling factor */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $factor = null,
        /** @var Money|null tax Total tax */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex'), Valid]
        public ?Money $tax = null,
        /** @var Money|null net Total item cost */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex'), Valid]
        public ?Money $net = null,
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        #[FhirProperty(
            fhirType: 'positiveInt',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive',
        )]
        public array $noteNumber = [],
        /** @var ExplanationOfBenefitItemReviewOutcome|null reviewOutcome Additem detail level adjudication results */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex'), Valid]
        public ?ExplanationOfBenefitItemReviewOutcome $reviewOutcome = null,
        /** @var array<ExplanationOfBenefitItemAdjudication> adjudication Added items adjudication */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication',
        )]
        #[Valid]
        public array $adjudication = [],
        /** @var array<ExplanationOfBenefitAddItemDetailSubDetail> subDetail Insurer added line items */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ExplanationOfBenefit\ExplanationOfBenefitAddItemDetailSubDetail',
        )]
        #[Valid]
        public array $subDetail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

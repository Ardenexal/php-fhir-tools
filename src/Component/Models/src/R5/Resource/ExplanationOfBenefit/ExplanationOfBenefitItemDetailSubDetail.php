<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Third-tier of goods and services.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item.detail.subDetail', fhirVersion: 'R5')]
class ExplanationOfBenefitItemDetailSubDetail extends BackboneElement
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
        /** @var PositiveIntPrimitive|null sequence Product or service provided */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
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
        /** @var CodeableConcept|null category Benefit classification */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $category = null,
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
        /** @var array<CodeableConcept> programCode Program the product or service is provided under */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $programCode = [],
        /** @var Money|null patientPaid Paid by the patient */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $patientPaid = null,
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
        /** @var array<Reference> udi Unique device identifier */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $udi = [],
        /** @var array<PositiveIntPrimitive> noteNumber Applicable note numbers */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
        public array $noteNumber = [],
        /** @var ExplanationOfBenefitItemReviewOutcome|null reviewOutcome Subdetail level adjudication results */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?ExplanationOfBenefitItemReviewOutcome $reviewOutcome = null,
        /** @var array<ExplanationOfBenefitItemAdjudication> adjudication Subdetail level adjudication details */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication',
        )]
        public array $adjudication = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

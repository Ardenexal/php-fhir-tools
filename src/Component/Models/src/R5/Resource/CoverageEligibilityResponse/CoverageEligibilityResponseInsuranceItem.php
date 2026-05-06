<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\CoverageEligibilityResponse;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @description Benefits and optionally current balances, and authorization details by category or service.
 */
#[FHIRBackboneElement(
    parentResource: 'CoverageEligibilityResponse',
    elementPath: 'CoverageEligibilityResponse.insurance.item',
    fhirVersion: 'R5',
)]
class CoverageEligibilityResponseInsuranceItem extends BackboneElement
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
        /** @var CodeableConcept|null category Benefit classification */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null productOrService Billing, service, product, or drug code */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $productOrService = null,
        /** @var array<CodeableConcept> modifier Product or service billing modifiers */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $modifier = [],
        /** @var Reference|null provider Performing practitioner */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $provider = null,
        /** @var bool|null excluded Excluded from the plan */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $excluded = null,
        /** @var StringPrimitive|string|null name Short name for the benefit */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null description Description of the benefit or services covered */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var CodeableConcept|null network In or out of network */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $network = null,
        /** @var CodeableConcept|null unit Individual or family */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $unit = null,
        /** @var CodeableConcept|null term Annual or lifetime */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $term = null,
        /** @var array<CoverageEligibilityResponseInsuranceItemBenefit> benefit Benefit Summary */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\CoverageEligibilityResponse\CoverageEligibilityResponseInsuranceItemBenefit',
        )]
        public array $benefit = [],
        /** @var bool|null authorizationRequired Authorization required flag */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $authorizationRequired = null,
        /** @var array<CodeableConcept> authorizationSupporting Type of required supporting materials */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $authorizationSupporting = [],
        /** @var UriPrimitive|null authorizationUrl Preauthorization requirements endpoint */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $authorizationUrl = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

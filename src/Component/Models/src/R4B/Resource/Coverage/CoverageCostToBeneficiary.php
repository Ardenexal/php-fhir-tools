<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Coverage;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A suite of codes indicating the cost category and associated amount which have been detailed in the policy and may have been  included on the health card.
 */
#[FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.costToBeneficiary', fhirVersion: 'R4B')]
class CoverageCostToBeneficiary extends BackboneElement
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
        /** @var CodeableConcept|null type Cost category */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var Quantity|Money|null value The amount or percentage due from the beneficiary */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isRequired: true,
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Quantity',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity',
                    'jsonKey'      => 'valueQuantity',
                ],
                [
                    'fhirType'     => 'Money',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Money',
                    'jsonKey'      => 'valueMoney',
                ],
            ],
        )]
        #[NotBlank]
        public Quantity|Money|null $value = null,
        /** @var array<CoverageCostToBeneficiaryException> exception Exceptions for patient payments */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Coverage\CoverageCostToBeneficiaryException',
        )]
        public array $exception = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

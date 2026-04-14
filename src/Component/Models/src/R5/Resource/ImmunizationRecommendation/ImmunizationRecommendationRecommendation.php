<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ImmunizationRecommendation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Vaccine administration recommendations.
 */
#[FHIRBackboneElement(
    parentResource: 'ImmunizationRecommendation',
    elementPath: 'ImmunizationRecommendation.recommendation',
    fhirVersion: 'R5',
)]
class ImmunizationRecommendationRecommendation extends BackboneElement
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
        /** @var array<CodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $vaccineCode = [],
        /** @var array<CodeableConcept> targetDisease Disease to be immunized against */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $targetDisease = [],
        /** @var array<CodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $contraindicatedVaccineCode = [],
        /** @var CodeableConcept|null forecastStatus Vaccine recommendation status */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $forecastStatus = null,
        /** @var array<CodeableConcept> forecastReason Vaccine administration status reason */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $forecastReason = [],
        /** @var array<ImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ImmunizationRecommendation\ImmunizationRecommendationRecommendationDateCriterion',
        )]
        public array $dateCriterion = [],
        /** @var MarkdownPrimitive|null description Protocol details */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var StringPrimitive|string|null series Name of vaccination series */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $series = null,
        /** @var StringPrimitive|string|null doseNumber Recommended dose number within series */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $doseNumber = null,
        /** @var StringPrimitive|string|null seriesDoses Recommended number of doses for immunity */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $seriesDoses = null,
        /** @var array<Reference> supportingImmunization Past immunizations supporting recommendation */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $supportingImmunization = [],
        /** @var array<Reference> supportingPatientInformation Patient observations supporting recommendation */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $supportingPatientInformation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ClinicalUseDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description Specifics for when this is an indication.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.indication', fhirVersion: 'R5')]
class ClinicalUseDefinitionIndication extends BackboneElement
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
        /** @var CodeableReference|null diseaseSymptomProcedure The situation that is being documented as an indicaton for this item */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ObservationDefinition'])]
        public ?CodeableReference $diseaseSymptomProcedure = null,
        /** @var CodeableReference|null diseaseStatus The status of the disease or symptom for the indication */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ObservationDefinition'])]
        public ?CodeableReference $diseaseStatus = null,
        /** @var array<CodeableReference> comorbidity A comorbidity or coinfection as part of the indication */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ObservationDefinition'])]
        public array $comorbidity = [],
        /** @var CodeableReference|null intendedEffect The intended effect, aim or strategy to be achieved */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/product-intended-use', strength: 'preferred'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ObservationDefinition'])]
        public ?CodeableReference $intendedEffect = null,
        /** @var Range|StringPrimitive|string|null duration Timing or duration information */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Range',
                    'jsonKey'      => 'durationRange',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'durationString',
                ],
            ],
        )]
        public Range|StringPrimitive|string|null $duration = null,
        /** @var array<Reference> undesirableEffect An unwanted side effect or negative outcome of the subject of this resource when being used for this indication */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ClinicalUseDefinition'])]
        public array $undesirableEffect = [],
        /** @var Expression|null applicability An expression that returns true or false, indicating whether the indication is applicable or not, after having applied its other elements */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
        public ?Expression $applicability = null,
        /** @var array<ClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy The use of the medicinal product in relation to other therapies described as part of the indication */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ClinicalUseDefinition\ClinicalUseDefinitionContraindicationOtherTherapy',
        )]
        public array $otherTherapy = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\SpecimenDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\SpecimenContainedPreferenceType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specimen conditioned in a container as expected by the testing laboratory.
 */
#[FHIRBackboneElement(parentResource: 'SpecimenDefinition', elementPath: 'SpecimenDefinition.typeTested', fhirVersion: 'R5')]
class SpecimenDefinitionTypeTested extends BackboneElement
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
        /** @var bool|null isDerived Primary or secondary specimen */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $isDerived = null,
        /** @var CodeableConcept|null type Type of intended specimen */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var SpecimenContainedPreferenceType|null preference preferred | alternate */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?SpecimenContainedPreferenceType $preference = null,
        /** @var SpecimenDefinitionTypeTestedContainer|null container The specimen's container */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?SpecimenDefinitionTypeTestedContainer $container = null,
        /** @var MarkdownPrimitive|null requirement Requirements for specimen delivery and special handling */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $requirement = null,
        /** @var Duration|null retentionTime The usual time for retaining this kind of specimen */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $retentionTime = null,
        /** @var bool|null singleUse Specimen for single use only */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $singleUse = null,
        /** @var array<CodeableConcept> rejectionCriterion Criterion specified for specimen rejection */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $rejectionCriterion = [],
        /** @var array<SpecimenDefinitionTypeTestedHandling> handling Specimen handling before testing */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SpecimenDefinition\SpecimenDefinitionTypeTestedHandling',
        )]
        public array $handling = [],
        /** @var array<CodeableConcept> testingDestination Where the specimen will be tested */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $testingDestination = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ObservationDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ObservationRangeCategoryType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description A set of qualified values associated with a context and a set of conditions -  provides a range for quantitative and ordinal observations and a collection of value sets for qualitative observations.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.qualifiedValue', fhirVersion: 'R5')]
class ObservationDefinitionQualifiedValue extends BackboneElement
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
        /** @var CodeableConcept|null context Context qualifier for the set of qualified values */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $context = null,
        /** @var array<CodeableConcept> appliesTo Targetted population for the set of qualified values */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $appliesTo = [],
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AdministrativeGenderType $gender = null,
        /** @var Range|null age Applicable age range for the set of qualified values */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $age = null,
        /** @var Range|null gestationalAge Applicable gestational age range for the set of qualified values */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $gestationalAge = null,
        /** @var StringPrimitive|string|null condition Condition associated with the set of qualified values */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $condition = null,
        /** @var ObservationRangeCategoryType|null rangeCategory reference | critical | absolute */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ObservationRangeCategoryType $rangeCategory = null,
        /** @var Range|null range The range for continuous or ordinal observations */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $range = null,
        /** @var CanonicalPrimitive|null validCodedValueSet Value set of valid coded values as part of this set of qualified values */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $validCodedValueSet = null,
        /** @var CanonicalPrimitive|null normalCodedValueSet Value set of normal coded values as part of this set of qualified values */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $normalCodedValueSet = null,
        /** @var CanonicalPrimitive|null abnormalCodedValueSet Value set of abnormal coded values as part of this set of qualified values */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $abnormalCodedValueSet = null,
        /** @var CanonicalPrimitive|null criticalCodedValueSet Value set of critical coded values as part of this set of qualified values */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $criticalCodedValueSet = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

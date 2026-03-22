<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\AdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ObservationRangeCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @description Multiple  ranges of results qualified by different contexts for ordinal or continuous observations conforming to this ObservationDefinition.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.qualifiedInterval', fhirVersion: 'R4B')]
class ObservationDefinitionQualifiedInterval extends BackboneElement
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
        /** @var ObservationRangeCategoryType|null category reference | critical | absolute */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ObservationRangeCategoryType $category = null,
        /** @var Range|null range The interval itself, for continuous or ordinal observations */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $range = null,
        /** @var CodeableConcept|null context Range context qualifier */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $context = null,
        /** @var array<CodeableConcept> appliesTo Targetted population of the range */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $appliesTo = [],
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AdministrativeGenderType $gender = null,
        /** @var Range|null age Applicable age range, if relevant */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $age = null,
        /** @var Range|null gestationalAge Applicable gestational age range, if relevant */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $gestationalAge = null,
        /** @var StringPrimitive|string|null condition Condition associated with the reference range */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $condition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

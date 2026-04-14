<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/DataRequirement
 *
 * @description Describes a required data item for evaluation in terms of the type of data, and optional code or date-based filters of the data.
 */
#[FHIRComplexType(typeName: 'DataRequirement', fhirVersion: 'R5')]
class DataRequirement extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var FHIRTypesType|null type The type of the required data */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?FHIRTypesType $type = null,
        /** @var array<CanonicalPrimitive> profile The profile of the required data */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $profile = [],
        /** @var CodeableConcept|Reference|null subject E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
                    'jsonKey'      => 'subjectCodeableConcept',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'subjectReference',
                ],
            ],
        )]
        public CodeableConcept|Reference|null $subject = null,
        /** @var array<StringPrimitive|string> mustSupport Indicates specific structure elements that are referenced by the knowledge module */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $mustSupport = [],
        /** @var array<DataRequirementCodeFilter> codeFilter What codes are expected */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirementCodeFilter',
        )]
        public array $codeFilter = [],
        /** @var array<DataRequirementDateFilter> dateFilter What dates/date ranges are expected */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirementDateFilter',
        )]
        public array $dateFilter = [],
        /** @var array<DataRequirementValueFilter> valueFilter What values are expected */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirementValueFilter',
        )]
        public array $valueFilter = [],
        /** @var PositiveIntPrimitive|null limit Number of results */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $limit = null,
        /** @var array<DataRequirementSort> sort Order of the results */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\DataRequirementSort',
        )]
        public array $sort = [],
    ) {
        parent::__construct($id, $extension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ObservationDataTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationDefinition\ObservationDefinitionQualifiedInterval;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationDefinition\ObservationDefinitionQuantitativeDetails;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ObservationDefinition
 *
 * @description Set of definitional characteristics for a kind of observation or measurement produced or consumed by an orderable health care service.
 */
#[FhirResource(
    type: 'ObservationDefinition',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ObservationDefinition',
    fhirVersion: 'R4B',
)]
class ObservationDefinitionResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> category Category of observation */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var CodeableConcept|null code Type of observation (code / type) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var array<Identifier> identifier Business identifier for this ObservationDefinition instance */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var array<ObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $permittedDataType = [],
        /** @var bool|null multipleResultsAllowed Multiple results allowed */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $multipleResultsAllowed = null,
        /** @var CodeableConcept|null method Method used to produce the observation */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $method = null,
        /** @var StringPrimitive|string|null preferredReportName Preferred report name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $preferredReportName = null,
        /** @var ObservationDefinitionQuantitativeDetails|null quantitativeDetails Characteristics of quantitative results */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ObservationDefinitionQuantitativeDetails $quantitativeDetails = null,
        /** @var array<ObservationDefinitionQualifiedInterval> qualifiedInterval Qualified range for continuous and ordinal observation results */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationDefinition\ObservationDefinitionQualifiedInterval',
        )]
        public array $qualifiedInterval = [],
        /** @var Reference|null validCodedValueSet Value set of valid coded values for the observations conforming to this ObservationDefinition */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $validCodedValueSet = null,
        /** @var Reference|null normalCodedValueSet Value set of normal coded values for the observations conforming to this ObservationDefinition */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $normalCodedValueSet = null,
        /** @var Reference|null abnormalCodedValueSet Value set of abnormal coded values for the observations conforming to this ObservationDefinition */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $abnormalCodedValueSet = null,
        /** @var Reference|null criticalCodedValueSet Value set of critical coded values for the observations conforming to this ObservationDefinition */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $criticalCodedValueSet = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

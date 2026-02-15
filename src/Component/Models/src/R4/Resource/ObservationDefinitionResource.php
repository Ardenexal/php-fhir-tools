<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationDataTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition\ObservationDefinitionQualifiedInterval;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition\ObservationDefinitionQuantitativeDetails;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ObservationDefinition',
    fhirVersion: 'R4',
)]
class ObservationDefinitionResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> category Category of observation */
        public array $category = [],
        /** @var CodeableConcept|null code Type of observation (code / type) */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var array<Identifier> identifier Business identifier for this ObservationDefinition instance */
        public array $identifier = [],
        /** @var array<ObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
        public array $permittedDataType = [],
        /** @var bool|null multipleResultsAllowed Multiple results allowed */
        public ?bool $multipleResultsAllowed = null,
        /** @var CodeableConcept|null method Method used to produce the observation */
        public ?CodeableConcept $method = null,
        /** @var StringPrimitive|string|null preferredReportName Preferred report name */
        public StringPrimitive|string|null $preferredReportName = null,
        /** @var ObservationDefinitionQuantitativeDetails|null quantitativeDetails Characteristics of quantitative results */
        public ?ObservationDefinitionQuantitativeDetails $quantitativeDetails = null,
        /** @var array<ObservationDefinitionQualifiedInterval> qualifiedInterval Qualified range for continuous and ordinal observation results */
        public array $qualifiedInterval = [],
        /** @var Reference|null validCodedValueSet Value set of valid coded values for the observations conforming to this ObservationDefinition */
        public ?Reference $validCodedValueSet = null,
        /** @var Reference|null normalCodedValueSet Value set of normal coded values for the observations conforming to this ObservationDefinition */
        public ?Reference $normalCodedValueSet = null,
        /** @var Reference|null abnormalCodedValueSet Value set of abnormal coded values for the observations conforming to this ObservationDefinition */
        public ?Reference $abnormalCodedValueSet = null,
        /** @var Reference|null criticalCodedValueSet Value set of critical coded values for the observations conforming to this ObservationDefinition */
        public ?Reference $criticalCodedValueSet = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

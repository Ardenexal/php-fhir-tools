<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
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
class FHIRObservationDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> category Category of observation */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Type of observation (code / type) */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRIdentifier> identifier Business identifier for this ObservationDefinition instance */
        public array $identifier = [],
        /** @var array<FHIRObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
        public array $permittedDataType = [],
        /** @var FHIRBoolean|null multipleResultsAllowed Multiple results allowed */
        public ?FHIRBoolean $multipleResultsAllowed = null,
        /** @var FHIRCodeableConcept|null method Method used to produce the observation */
        public ?FHIRCodeableConcept $method = null,
        /** @var FHIRString|string|null preferredReportName Preferred report name */
        public FHIRString|string|null $preferredReportName = null,
        /** @var FHIRObservationDefinitionQuantitativeDetails|null quantitativeDetails Characteristics of quantitative results */
        public ?FHIRObservationDefinitionQuantitativeDetails $quantitativeDetails = null,
        /** @var array<FHIRObservationDefinitionQualifiedInterval> qualifiedInterval Qualified range for continuous and ordinal observation results */
        public array $qualifiedInterval = [],
        /** @var FHIRReference|null validCodedValueSet Value set of valid coded values for the observations conforming to this ObservationDefinition */
        public ?FHIRReference $validCodedValueSet = null,
        /** @var FHIRReference|null normalCodedValueSet Value set of normal coded values for the observations conforming to this ObservationDefinition */
        public ?FHIRReference $normalCodedValueSet = null,
        /** @var FHIRReference|null abnormalCodedValueSet Value set of abnormal coded values for the observations conforming to this ObservationDefinition */
        public ?FHIRReference $abnormalCodedValueSet = null,
        /** @var FHIRReference|null criticalCodedValueSet Value set of critical coded values for the observations conforming to this ObservationDefinition */
        public ?FHIRReference $criticalCodedValueSet = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContributor;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCount;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDistance;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRParameterDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatioRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSampledData;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSignature;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIROid;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUuid;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ElementDefinition
 *
 * @description Captures constraints on each element within the resource, profile, or extension.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ElementDefinition', elementPath: 'ElementDefinition', fhirVersion: 'R4B')]
class FHIRElementDefinition extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null path Path of the element in the hierarchy of elements */
        #[NotBlank]
        public FHIRString|string|null $path = null,
        /** @var array<FHIRPropertyRepresentationType> representation xmlAttr | xmlText | typeAttr | cdaText | xhtml */
        public array $representation = [],
        /** @var FHIRString|string|null sliceName Name for this particular element (in a set of slices) */
        public FHIRString|string|null $sliceName = null,
        /** @var FHIRBoolean|null sliceIsConstraining If this slice definition constrains an inherited slice definition (or not) */
        public ?FHIRBoolean $sliceIsConstraining = null,
        /** @var FHIRString|string|null label Name for element to display with or prompt for element */
        public FHIRString|string|null $label = null,
        /** @var array<FHIRCoding> code Corresponding codes in terminologies */
        public array $code = [],
        /** @var FHIRElementDefinitionSlicing|null slicing This element is sliced - slices follow */
        public ?FHIRElementDefinitionSlicing $slicing = null,
        /** @var FHIRString|string|null short Concise definition for space-constrained presentation */
        public FHIRString|string|null $short = null,
        /** @var FHIRMarkdown|null definition Full formal definition as narrative text */
        public ?FHIRMarkdown $definition = null,
        /** @var FHIRMarkdown|null comment Comments about the use of this element */
        public ?FHIRMarkdown $comment = null,
        /** @var FHIRMarkdown|null requirements Why this resource has been created */
        public ?FHIRMarkdown $requirements = null,
        /** @var array<FHIRString|string> alias Other names */
        public array $alias = [],
        /** @var FHIRUnsignedInt|null min Minimum Cardinality */
        public ?FHIRUnsignedInt $min = null,
        /** @var FHIRString|string|null max Maximum Cardinality (a number or *) */
        public FHIRString|string|null $max = null,
        /** @var FHIRElementDefinitionBase|null base Base definition information for tools */
        public ?FHIRElementDefinitionBase $base = null,
        /** @var FHIRUri|null contentReference Reference to definition of content for the element */
        public ?FHIRUri $contentReference = null,
        /** @var array<FHIRElementDefinitionType> type Data type and Profile for this element */
        public array $type = [],
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null defaultValueX Specified value if missing from instance */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null $defaultValueX = null,
        /** @var FHIRMarkdown|null meaningWhenMissing Implicit meaning when this element is missing */
        public ?FHIRMarkdown $meaningWhenMissing = null,
        /** @var FHIRString|string|null orderMeaning What the order of the elements means */
        public FHIRString|string|null $orderMeaning = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null fixedX Value must be exactly this */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null $fixedX = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null patternX Value must have at least these property values */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null $patternX = null,
        /** @var array<FHIRElementDefinitionExample> example Example value (as defined for type) */
        public array $example = [],
        /** @var FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null minValueX Minimum Allowed Value (for some types) */
        public FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null $minValueX = null,
        /** @var FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null maxValueX Maximum Allowed Value (for some types) */
        public FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null $maxValueX = null,
        /** @var FHIRInteger|null maxLength Max length for strings */
        public ?FHIRInteger $maxLength = null,
        /** @var array<FHIRId> condition Reference to invariant about presence */
        public array $condition = [],
        /** @var array<FHIRElementDefinitionConstraint> constraint Condition that must evaluate to true */
        public array $constraint = [],
        /** @var FHIRBoolean|null mustSupport If the element must be supported */
        public ?FHIRBoolean $mustSupport = null,
        /** @var FHIRBoolean|null isModifier If this modifies the meaning of other elements */
        public ?FHIRBoolean $isModifier = null,
        /** @var FHIRString|string|null isModifierReason Reason that this element is marked as a modifier */
        public FHIRString|string|null $isModifierReason = null,
        /** @var FHIRBoolean|null isSummary Include when _summary = true? */
        public ?FHIRBoolean $isSummary = null,
        /** @var FHIRElementDefinitionBinding|null binding ValueSet details if this is coded */
        public ?FHIRElementDefinitionBinding $binding = null,
        /** @var array<FHIRElementDefinitionMapping> mapping Map element to another set of definitions */
        public array $mapping = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger64;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIROid;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUuid;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ElementDefinition
 *
 * @description Captures constraints on each element within the resource, profile, or extension.
 */
#[FHIRComplexType(typeName: 'ElementDefinition', fhirVersion: 'R5')]
class FHIRElementDefinition extends FHIRBackboneType
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
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null defaultValueX Specified value if missing from instance */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null $defaultValueX = null,
        /** @var FHIRMarkdown|null meaningWhenMissing Implicit meaning when this element is missing */
        public ?FHIRMarkdown $meaningWhenMissing = null,
        /** @var FHIRString|string|null orderMeaning What the order of the elements means */
        public FHIRString|string|null $orderMeaning = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null fixedX Value must be exactly this */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null $fixedX = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null patternX Value must have at least these property values */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null $patternX = null,
        /** @var array<FHIRElementDefinitionExample> example Example value (as defined for type) */
        public array $example = [],
        /** @var FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRInteger64|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null minValueX Minimum Allowed Value (for some types) */
        public FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRInteger64|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null $minValueX = null,
        /** @var FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRInteger64|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null maxValueX Maximum Allowed Value (for some types) */
        public FHIRDate|FHIRDateTime|FHIRInstant|FHIRTime|FHIRDecimal|FHIRInteger|FHIRInteger64|FHIRPositiveInt|FHIRUnsignedInt|FHIRQuantity|null $maxValueX = null,
        /** @var FHIRInteger|null maxLength Max length for string type data */
        public ?FHIRInteger $maxLength = null,
        /** @var array<FHIRId> condition Reference to invariant about presence */
        public array $condition = [],
        /** @var array<FHIRElementDefinitionConstraint> constraint Condition that must evaluate to true */
        public array $constraint = [],
        /** @var FHIRBoolean|null mustHaveValue For primitives, that a value must be present - not replaced by an extension */
        public ?FHIRBoolean $mustHaveValue = null,
        /** @var array<FHIRCanonical> valueAlternatives Extensions that are allowed to replace a primitive value */
        public array $valueAlternatives = [],
        /** @var FHIRBoolean|null mustSupport If the element must be supported (discouraged - see obligations) */
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

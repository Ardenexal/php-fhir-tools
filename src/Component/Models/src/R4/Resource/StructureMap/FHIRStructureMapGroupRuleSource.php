<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContributor;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCount;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDistance;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRParameterDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSampledData;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSignature;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIROid;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUuid;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Source inputs to the mapping.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.source', fhirVersion: 'R4')]
class FHIRStructureMapGroupRuleSource extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null context Type or variable this rule applies to */
        #[NotBlank]
        public ?FHIRId $context = null,
        /** @var FHIRInteger|null min Specified minimum cardinality */
        public ?FHIRInteger $min = null,
        /** @var FHIRString|string|null max Specified maximum cardinality (number or *) */
        public FHIRString|string|null $max = null,
        /** @var FHIRString|string|null type Rule only applies if source has this type */
        public FHIRString|string|null $type = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|FHIRMeta|null defaultValueX Default value if no value exists */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|FHIRMeta|null $defaultValueX = null,
        /** @var FHIRString|string|null element Optional field for this source */
        public FHIRString|string|null $element = null,
        /** @var FHIRStructureMapSourceListModeType|null listMode first | not_first | last | not_last | only_one */
        public ?FHIRStructureMapSourceListModeType $listMode = null,
        /** @var FHIRId|null variable Named context for field, if a field is specified */
        public ?FHIRId $variable = null,
        /** @var FHIRString|string|null condition FHIRPath expression  - must be true or the rule does not apply */
        public FHIRString|string|null $condition = null,
        /** @var FHIRString|string|null check FHIRPath expression  - must be true or the mapping engine throws an error instead of completing */
        public FHIRString|string|null $check = null,
        /** @var FHIRString|string|null logMessage Message to put in log if source exists (FHIRPath) */
        public FHIRString|string|null $logMessage = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

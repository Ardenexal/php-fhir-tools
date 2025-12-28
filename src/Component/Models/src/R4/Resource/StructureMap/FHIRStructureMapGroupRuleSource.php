<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
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
        public ?\FHIRId $context = null,
        /** @var FHIRInteger|null min Specified minimum cardinality */
        public ?\FHIRInteger $min = null,
        /** @var FHIRString|string|null max Specified maximum cardinality (number or *) */
        public \FHIRString|string|null $max = null,
        /** @var FHIRString|string|null type Rule only applies if source has this type */
        public \FHIRString|string|null $type = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|FHIRMeta|null defaultValueX Default value if no value exists */
        public \FHIRBase64Binary|\FHIRBoolean|\FHIRCanonical|\FHIRCode|\FHIRDate|\FHIRDateTime|\FHIRDecimal|\FHIRId|\FHIRInstant|\FHIRInteger|\FHIRMarkdown|\FHIROid|\FHIRPositiveInt|\FHIRString|string|\FHIRTime|\FHIRUnsignedInt|\FHIRUri|\FHIRUrl|\FHIRUuid|\FHIRAddress|\FHIRAge|\FHIRAnnotation|\FHIRAttachment|\FHIRCodeableConcept|\FHIRCoding|\FHIRContactPoint|\FHIRCount|\FHIRDistance|\FHIRDuration|\FHIRHumanName|\FHIRIdentifier|\FHIRMoney|\FHIRPeriod|\FHIRQuantity|\FHIRRange|\FHIRRatio|\FHIRReference|\FHIRSampledData|\FHIRSignature|\FHIRTiming|\FHIRContactDetail|\FHIRContributor|\FHIRDataRequirement|\FHIRExpression|\FHIRParameterDefinition|\FHIRRelatedArtifact|\FHIRTriggerDefinition|\FHIRUsageContext|\FHIRDosage|\FHIRMeta|null $defaultValueX = null,
        /** @var FHIRString|string|null element Optional field for this source */
        public \FHIRString|string|null $element = null,
        /** @var FHIRStructureMapSourceListModeType|null listMode first | not_first | last | not_last | only_one */
        public ?\FHIRStructureMapSourceListModeType $listMode = null,
        /** @var FHIRId|null variable Named context for field, if a field is specified */
        public ?\FHIRId $variable = null,
        /** @var FHIRString|string|null condition FHIRPath expression  - must be true or the rule does not apply */
        public \FHIRString|string|null $condition = null,
        /** @var FHIRString|string|null check FHIRPath expression  - must be true or the mapping engine throws an error instead of completing */
        public \FHIRString|string|null $check = null,
        /** @var FHIRString|string|null logMessage Message to put in log if source exists (FHIRPath) */
        public \FHIRString|string|null $logMessage = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A parameter passed to or received from the operation.
 */
#[FHIRBackboneElement(parentResource: 'Parameters', elementPath: 'Parameters.parameter', fhirVersion: 'R5')]
class FHIRParametersParameter extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Name from the definition */
        #[NotBlank]
        public \FHIRString|string|null $name = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null valueX If parameter is a data type */
        public \FHIRBase64Binary|\FHIRBoolean|\FHIRCanonical|\FHIRCode|\FHIRDate|\FHIRDateTime|\FHIRDecimal|\FHIRId|\FHIRInstant|\FHIRInteger|\FHIRInteger64|\FHIRMarkdown|\FHIROid|\FHIRPositiveInt|\FHIRString|string|\FHIRTime|\FHIRUnsignedInt|\FHIRUri|\FHIRUrl|\FHIRUuid|\FHIRAddress|\FHIRAge|\FHIRAnnotation|\FHIRAttachment|\FHIRCodeableConcept|\FHIRCodeableReference|\FHIRCoding|\FHIRContactPoint|\FHIRCount|\FHIRDistance|\FHIRDuration|\FHIRHumanName|\FHIRIdentifier|\FHIRMoney|\FHIRPeriod|\FHIRQuantity|\FHIRRange|\FHIRRatio|\FHIRRatioRange|\FHIRReference|\FHIRSampledData|\FHIRSignature|\FHIRTiming|\FHIRContactDetail|\FHIRDataRequirement|\FHIRExpression|\FHIRParameterDefinition|\FHIRRelatedArtifact|\FHIRTriggerDefinition|\FHIRUsageContext|\FHIRAvailability|\FHIRExtendedContactDetail|\FHIRDosage|\FHIRMeta|null $valueX = null,
        /** @var FHIRResource|null resource If parameter is a whole resource */
        public ?\FHIRResource $resource = null,
        /** @var array<FHIRParametersParameter> part Named part of a multi-part parameter */
        public array $part = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

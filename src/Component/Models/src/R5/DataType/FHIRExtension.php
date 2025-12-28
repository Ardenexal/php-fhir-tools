<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Extension
 *
 * @description Optional Extension Element - found in all resources.
 */
#[FHIRComplexType(typeName: 'Extension', fhirVersion: 'R5')]
class FHIRExtension extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null url identifies the meaning of the extension */
        #[NotBlank]
        public ?string $url = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null valueX Value of extension */
        public \FHIRBase64Binary|\FHIRBoolean|\FHIRCanonical|\FHIRCode|\FHIRDate|\FHIRDateTime|\FHIRDecimal|\FHIRId|\FHIRInstant|\FHIRInteger|\FHIRInteger64|\FHIRMarkdown|\FHIROid|\FHIRPositiveInt|\FHIRString|string|\FHIRTime|\FHIRUnsignedInt|\FHIRUri|\FHIRUrl|\FHIRUuid|\FHIRAddress|\FHIRAge|\FHIRAnnotation|\FHIRAttachment|\FHIRCodeableConcept|\FHIRCodeableReference|\FHIRCoding|\FHIRContactPoint|\FHIRCount|\FHIRDistance|\FHIRDuration|\FHIRHumanName|\FHIRIdentifier|\FHIRMoney|\FHIRPeriod|\FHIRQuantity|\FHIRRange|\FHIRRatio|\FHIRRatioRange|\FHIRReference|\FHIRSampledData|\FHIRSignature|\FHIRTiming|\FHIRContactDetail|\FHIRDataRequirement|\FHIRExpression|\FHIRParameterDefinition|\FHIRRelatedArtifact|\FHIRTriggerDefinition|\FHIRUsageContext|\FHIRAvailability|\FHIRExtendedContactDetail|\FHIRDosage|\FHIRMeta|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
